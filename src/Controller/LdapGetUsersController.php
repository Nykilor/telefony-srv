<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\LdapUser;
use App\Entity\PhoneNumbers;
use App\Entity\Domain;
use Adldap\Adldap;

/**
 * @Route("/api")
 */
class LdapGetUsersController extends AbstractController {
    /**
     * @Route("/getUsersFromLdap")
     */
    public function getUsersFromLdap() {
      // $domain = $this->connectByLdap();
      // $users = $domain->search()->users()->in("OU=User,OU=ima-pl,OU=Administration,DC=ima-pl,DC=local")->get();
      $users = unserialize(file_get_contents("serializedforhome.txt"));
      foreach ($users as $key => $value) {
        if($key === 0) continue;
        echo "<pre>";
        //Write on github about this
        var_dump($value);
        exit();
      }
      return $this->json("test");
    }

    private function createUserDatabaseEntry(\Adldap\Models\User $user) {
      $repository = $this->getDoctrine()->getRepository(LdapUser::class);

      if(!empty($repository->findBy($user))) {

      }
    }

    private function createPhoneDatabaseEntry(\Adldap\Models\User $user) {
      $repository = $this->getDoctrine()->getRepository(PhoneNumbers::class);
    }

    private function connectByLdap() {
      $domain_id = 2;
      $user = "migacz";
      $password = "Kupiemalucha129";
      $repository = $this->getDoctrine()->getRepository(Domain::class);
      $entity = $repository->find($domain_id);
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

      $ad = new \Adldap\Adldap();
      $ad->addProvider($config);

      $provider = $ad->connect();

      return $provider;
    }
}
