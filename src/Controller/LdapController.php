<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Service\ActiveDirectoryFetch;
use App\Entity\Domain;

class LdapGetDataController extends AbstractController {
  /**
   * @Route(
   *     name="LdapGetData",
   *     path="/api/domain/LdapGetData/{id}",
   *     methods={"POST", "GET"},
   *     defaults={
   *      "_controller"="\App\Controller\LdapGetDataController::fetch",
   *      "_api_item_operation_name"="LdapGetData",
   *      "_api_resource_class"=Domain::class,
   *      "_api_receive"=true
   *     }
   * )
   */
    public function fetch(Domain $data, ActiveDirectoryFetch $class, Request $request) {
      $response = $class->fetchData($data);

      return $this->json($response);
    }

}
