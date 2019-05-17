<?php

namespace App\Service;

use JeroenDesloovere\VCard\VCard;

class VcfFactory {

  public $vcf;
  public $vcf_output;

  public function create($data) {
    if(is_array($data) && !array_key_exists("id", $data)) {
      $vcf_array = [];
      foreach ($data as $key => $ldapUserEntity) {
          $vcf_object = $this->createVcf($ldapUserEntity);
          $this->vcf[] = $vcf_object;
          $vcf_array[] = $vcf_object->getOutput();
      }
      $this->vcf_output = implode("", $vcf_array);
    } else {
      $vcf_object = $this->createVcf($data);
      $this->vcf = $vcf_object;
      $this->vcfOutput = $vcf_object->getOutput();
    }
  }

  protected function createVcf($array) {
    var_dump($array);
    exit();
    $vcard = new VCard();
    $arrayPhoneNumbers = $array['phoneNumbers'];
    $vcard->addName($array['lastName'], $array['firstName']);
    $vcard->addCompany($array['company']." - ".$array['department']);
    $vcard->addJobtitle($array['title']);
    $vcard->addEmail($array['email']);

    foreach ($arrayPhoneNumbers as $phoneNumber) {
        switch ($phone['type']) {
          case 'cell':
            $vcard->addPhoneNumber($phoneNumber['value'], "PREF;WORK;CELL;VOICE");
            break;
          case 'home':
            $vcard->addPhoneNumber($phoneNumber['value'], "HOME");
            break;
          case 'ipphone':
            $vcard->addPhoneNumber($phoneNumber['value'], "WORK;VOICE");
            break;
          default:
            throw new \Exception("Undefined type.", 1);
            break;
        }
    }

    return $vcard;
  }
}
