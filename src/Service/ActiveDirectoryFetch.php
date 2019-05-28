<?php

namespace App\Service;

use DateTime;

use App\Dto\LdapFetchInput;
use App\Entity\LdapUser;
use App\Entity\LdapPhoneNumbers;
use Adldap\Adldap;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Fetches the Ldap User data from given server and closes it into $this->ldapUser variable
 */
class ActiveDirectoryFetch
{
    public $ldapUser = [];
    protected $domain;
    protected $login;
    protected $password;
    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function fetchData(LdapFetchInput $domain)
    {
        $this->domain = $domain->domain;
        $this->login = $domain->login;
        $this->password = $domain->password;
        $this->query = $domain->query;

        $domain = $this->connectByLdap();

        //Either all users or by the given query
        switch ($this->query) {
            case 'allUsers':
            $users = $domain->search()->users()->get();
            break;

        default:
            $users = $domain->search()->users()->in($this->query)->get();
            break;
        }

        $databaseEntries = [];
        $counter = 0;

        foreach ($users as $key => $adldapUserModel) {
            $ldapUserEntity = $this->fetchOrCreateLdapUserEntity($adldapUserModel);
            if (!is_null($ldapUserEntity)) {
                $declaredDate = $ldapUserEntity->getWhenChanged();
                $newDate = new DateTime(str_replace(".0Z", "", $adldapUserModel->getUpdatedAt()));
                $ldapUserEntity = $this->fetchOrCreatePhoneNumbersEntity($adldapUserModel, $ldapUserEntity);

                if (is_null($declaredDate)) {
                    $letPersist = true;
                } elseif ($declaredDate->getTimestamp() < $newDate->getTimestamp()) {
                    $letPersist = true;
                } else {
                    $letPersist = false;
                }

                if ($letPersist) {
                    $ldapUserEntity->setWhenChanged($newDate);
                    $this->entityManager->persist($ldapUserEntity);
                    $counter++;
                    $this->ldapUser[] = $ldapUserEntity;
                }
                //Batch adding
                if ($counter === 3) {
                    $this->entityManager->flush();
                    $counter = 0;
                }
            }
        }
        //Add the rest
        if ($counter > 0) {
            $this->entityManager->flush();
        }

        return [
            "LdapUser" => $this->ldapUser
        ];
    }

    /**
     * Tries to get LdapUser entity by given login or creates a new one
     * @param  \Adldap\Models\User $adldapUserModel
     * @return LdapUser|null
     */
    private function fetchOrCreateLdapUserEntity(\Adldap\Models\User $adldapUserModel) : ?LdapUser
    {
        $repositoryLdapUser = $this->entityManager->getRepository(LdapUser::class);
        $userEntity = $repositoryLdapUser->findOneBy(["login" => $this->domain->getPrefix()."\\".$adldapUserModel->getAccountName()]);

        if (!($userEntity instanceof LdapUser)) {
            $userEntity = new LdapUser();
            $userEntity->setDomain($this->domain);
        }

        if (!is_null($adldapUserModel->getFirstName())) {
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

    /**
     * Tries to get or create LdapPhoneNumbers by given Adldap\Models\User nad App\Entity\LdapUser, adds them to persist list.
     * @param  \Adldap\Models\User $adldapUserModel
     * @param  LdapUser         $ldapUserEntity
     * @return LdapUser         If there's any LdapPhoneNumbers it is added the LdapUser->ldapPhoneNumbers collection
     */
    private function fetchOrCreatePhoneNumbersEntity(\Adldap\Models\User $adldapUserModel, LdapUser $ldapUserEntity) : LdapUser
    {
        $repository = $this->entityManager->getRepository(LdapPhoneNumbers::class);
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
            if (is_null($phone["value"])) {
                continue;
            }
            //Check if it exists in the database
            $phoneNumbersEntity = $repository->findOneBy(["value" => $phone["value"]]);
            //If no entry in database create a new Entity
            if (!($phoneNumbersEntity instanceof LdapPhoneNumbers)) {
                $phoneNumbersEntity = new LdapPhoneNumbers();
            } elseif ($phoneNumbersEntity->getLdapUser()->getId() === $ldapUserEntity->getId()) {
                //Continue if the current value didn't change it's owner
                continue;
            }

            $phoneNumbersEntity->setType($phone["type"]);
            $phoneNumbersEntity->setValue($phone["value"]);
            $this->entityManager->persist($phoneNumbersEntity);
            $ldapUserEntity->addLdapPhoneNumbers($phoneNumbersEntity);
        }

        return $ldapUserEntity;
    }
    /**
     * Connect to Ldap server by given credentials
     * @return  [description]
     */
    private function connectByLdap()
    {
        $user = $this->login;
        $password = $this->password;
        $entity = $this->domain;

        // Create the configuration array.
        $ldapClass = $entity->getConnectionSchema();
        switch ($ldapClass) {
            case 'OpenLDAP':
                $connection_schema = \Adldap\Schemas\OpenLDAP::class;
                break;
            case 'FreeIPA':
                $connection_schema = \Adldap\Schemas\FreeIPA::class;
                break;
            default:
                $connection_schema = \Adldap\Schemas\ActiveDirectory::class;
                break;
        }

        $config = [
            // Mandatory Configuration Options
            'hosts'            => $entity->getHosts(),
            'base_dn'          => $entity->getBaseDn(),
            'username'         => $user,
            'password'         => $password,
            // Optional Configuration Options
            'schema'           => $connection_schema,
            'account_prefix'   => $entity->getPrefix(),
            'port'             => $entity->getPort(),
            'use_ssl'          => $entity->getUseSsl(),
            'use_tls'          => $entity->getUseTls(),
            'version'          => $entity->getVersion(),
            'timeout'          => $entity->getTimeout(),
        ];

        if (!empty($entity->getCustom())) {
            $config["custom_options"] = $entity->getCustom();
        }

        $ad = new Adldap();
        $ad->addProvider($config);

        $provider = $ad->connect();

        return $provider;
    }
}
