<?php

namespace App\Controller;

use App\Service\VcfFactory;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Response;

class GenerateVcfController extends AbstractController {

    private $factory;

    public function __construct() {
      $this->factory = $factory;
    }

    public function __invoke($data) {
      if(is_array($data)) {
        $this->factory->create($data);
        $headers = [
            'Content-type' => "text/x-vcard; charset=utf-8",
            'Content-Disposition' => "attachment; filename=ldapusers.vcf",
            'Content-Length' => mb_strlen($this->factory->vcf_output, '8bit'),
            'Connection' => "close",
        ];

        return new Response(
          $this->factory->vcf_output,
          Response::HTTP_OK,
          $headers
        );
      } else {
        $this->factory->create($data);
        return $this->factory->vcf->download();
      }
    }

}
