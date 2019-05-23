<?php

namespace App\Controller;

use App\Dto\LdapFetchInput;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\ActiveDirectoryFetch;

class LdapFetchController extends AbstractController
{
    private $ad;

    public function __construct(ActiveDirectoryFetch $ad)
    {
        $this->ad = $ad;
    }

    public function __invoke(LdapFetchInput $data)
    {
        $response = $this->ad->fetchData($data);
        return $this->json($response);
    }
}
