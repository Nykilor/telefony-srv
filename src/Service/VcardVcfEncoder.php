<?php

namespace App\Service;

use JeroenDesloovere\VCard\VCard;

use App\Exception\SerializationEncodeOperationTypeException;
use App\Exception\PhoneNumbersUnknownTypeException;

class VcardVcfEncoder
{
    public $vcf;
    public $vcf_output;

    public function encode($data, $type) : void
    {
        if ($type === "collection") {
            $vcf_array = [];
            foreach ($data as $key => $ldapUser) {
                if ($ldapUser["is_visible"]) {
                    $vcf_object = $this->createVCard($ldapUser);
                    $this->vcf[] = $vcf_object;
                    $vcf_array[] = $vcf_object->getOutput();
                }
            }
            $this->vcf_output = implode("", $vcf_array);
        } elseif ($type === "item") {
            if ($data["is_visible"]) {
                $vcf_object = $this->createVCard($data);
                $this->vcf = $vcf_object;
                $this->vcf_output = $vcf_object->getOutput();
            }
        } else {
            throw new SerializationEncodeOperationTypeException("Unkown operation type.", 1);
        }
    }

    protected function createVCard($array) : VCard
    {
        $vcard = new VCard();
        $arrayPhoneNumbers = $array['phoneNumbers'];
        $vcard->addName($array['last_name'], $array['first_name']);
        $vcard->addCompany($array['company']." - ".$array['department']);
        $vcard->addJobtitle($array['title']);
        $vcard->addEmail($array['email']);

        foreach ($arrayPhoneNumbers as $phoneNumber) {
            switch ($phoneNumber['type']) {
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
            throw new PhoneNumbersUnknownTypeException("Undefined type.", 1);
            break;
        }
        }

        return $vcard;
    }
}
