<?php

namespace App\Service;
use DateTime;

use App\Dto\LdapFetchInput;
use App\Entity\LdapUser;
use App\Entity\PhoneNumbers;
use Adldap\Adldap;
use Doctrine\ORM\EntityManagerInterface;

class ActiveDirectoryFetch {

  public $userCount = 0;
  public $phoneCount = 0;
  protected $domain;
  protected $login;
  protected $password;
  protected $entityManager;

  public function __construct(EntityManagerInterface $entityManager) {
    $this->entityManager = $entityManager;
  }

  public function fetchData(LdapFetchInput $domain) {
    $this->domain = $domain->domain;
    $this->login = $domain->login;
    $this->password = $domain->password;
    $this->query = $domain->$query;

    $domain = $this->connectByLdap();

    switch ($this->query) {
      case 'allUsers':
        $users = $search->users()->get();
        break;

      default:
        $users = $domain->search()->users()->in("OU=User,OU=ima-pl,OU=Administration,DC=ima-pl,DC=local")->get();
        break;
    }

    // $users = unserialize(file_get_contents("serializedforhome.txt"));
    $databaseEntries = [];
    $counter = 0;
    foreach ($users as $key => $adldapUserModel) {
      //Write on github about this
      $ldapUserEntity = $this->getOrCreateLdapUserEntity($adldapUserModel);
      if(!is_null($ldapUserEntity)) {
        $declaredDate = $ldapUserEntity->getWhenChanged();
        $newDate = new DateTime(str_replace(".0Z", "", $adldapUserModel->getUpdatedAt()));
        $ldapUserEntity = $this->getOrCreatePhoneNumbersEntity($adldapUserModel, $ldapUserEntity);

        if(is_null($declaredDate)) {
          $letPersist = true;
        } else if($declaredDate->getTimestamp() < $newDate->getTimestamp()) {
          $letPersist = true;
        } else {
          $letPersist = false;
        }

        if($letPersist) {
          $ldapUserEntity->setWhenChanged($newDate);
          $this->entityManager->persist($ldapUserEntity);
          $counter++;
          $this->userCount++;
        }
        //Batch adding
        if($counter === 5) {
          $this->entityManager->flush();
          $counter = 0;
        }
      }
    }
    //Add the rest
    if($counter > 0 && $this->userCount > 0 || $this->phoneCount > 0) {
      $this->entityManager->flush();
    }

    return [
      "userCount" => $this->userCount,
      "phoneCount" => $this->phoneCount
    ];
  }

  private function getOrCreateLdapUserEntity(\Adldap\Models\User $adldapUserModel) : ?LdapUser {
    $repositoryLdapUser = $this->entityManager->getRepository(LdapUser::class);
    $userEntity = $repositoryLdapUser->findOneBy(["login" => $adldapUserModel->getAccountName()]);

    if(!($userEntity instanceof LdapUser)) {
      $userEntity = new LdapUser();
      $userEntity->setDomain($this->domain);
    }

    if(!is_null($adldapUserModel->getFirstName())) {
      $userEntity->setLogin($this->domain->getPrefix()."\\".$adldapUserModel->getAccountName());
      $userEntity->setFirstName($adldapUserModel->getFirstName());
      $userEntity->setLastName($adldapUserModel->getLastName());
      $userEntity->setDepartment($adldapUserModel->getDepartment());
      $userEntity->setEmail($adldapUserModel->getEmail());
      $userEntity->setCompany($adldapUserModel->getCompany());
      $userEntity->setDescription($adldapUserModel->getDescription());
      $userEntity->setTitle($adldapUserModel->getTitle());
      //THIS GOT TO BE IFXED BIURO =!= Department
      $userEntity->setBiuro($adldapUserModel->getDepartment());
    } else {
      $userEntity = null;
    }

    return $userEntity;
  }

  private function getOrCreatePhoneNumbersEntity(\Adldap\Models\User $adldapUserModel, LdapUser $ldapUserEntity) : LdapUser {
    $repository = $this->entityManager->getRepository(PhoneNumbers::class);
    $phones = [];

    $phones[] = [
      "type" => "cell",
      "value" => $adldapUserModel->getMobileNumber()
    ];

    $phones[] = [
      "type" => "home",
      "value" => $adldapUserModel->getHomePhone()
    ];

    $phones[] = [
      "type" => "ipphone",
      "value" => $adldapUserModel->getIpPhone()
    ];

    $phonesEntityArray = [];
    foreach ($phones as $phone) {
      //If there's no phone just go to the next one.
      if(is_null($phone["value"])) continue;
      //Check if it exists in the database
      $phoneNumbersEntity = $repository->findOneBy(["value" => $phone["value"]]);
      //If no entry in database create a new Entity
      if(!($phoneNumbersEntity instanceof PhoneNumbers)) {
        $phoneNumbersEntity = new PhoneNumbers();
      } else if($phoneNumbersEntity->getUser()->getId() === $ldapUserEntity->getId()) {
        //Continue if the current value didn't change it's owner
        continue;
      }

      $phoneNumbersEntity->setType($phone["type"]);
      $phoneNumbersEntity->setValue($phone["value"]);
      $this->entityManager->persist($phoneNumbersEntity);
      $this->phoneCount++;
      $ldapUserEntity->addPhoneNumbers($phoneNumbersEntity);
    }

    return $ldapUserEntity;

  }

  private function connectByLdap() {
    $user = $this->login;
    $password = $this->password;
    $entity = $this->domain;

    // Create the configuration array.
    $ldapClass = $entity->getConnectionSchema();
    //Do ogarniecia tak, żeby ładowało ok
    // $schema = '\Adldap\Schemas\ActiveDirectory::class';
    // var_dump($schema);
    // $class = new $schema();

    $config = [
        // Mandatory Configuration Options
        'hosts'            => $entity->getHosts(),
        'base_dn'          => $entity->getBaseDn(),
        'username'         => $entity->getPrefix()."\\".$user,
        'password'         => $password,
        // Optional Configuration Options
        'schema'           => \Adldap\Schemas\ActiveDirectory::class,
        'account_prefix'   => $entity->getPrefix(),
        'port'             => $entity->getPort(),
        'use_ssl'          => $entity->getUseSsl(),
        'use_tls'          => $entity->getUseTls(),
        'version'          => $entity->getVersion(),
        'timeout'          => $entity->getTimeout(),
    ];

    if(!empty($entity->getCustom())) {
      $config["custom_options"] = $entity->getCustom();
    }

    $ad = new Adldap();
    $ad->addProvider($config);

    $provider = $ad->connect();

    return $provider;
  }
}
