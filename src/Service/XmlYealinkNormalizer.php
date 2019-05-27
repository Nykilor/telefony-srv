<?php
namespace App\Service;

use App\Entity\LdapUser;
use App\Entity\LdapPhoneNumbers;

class XmlYealinkNormalizer
{
    public function normalize(LdapUser $ldapUser) : array
    {
        $new_array = [
          "@Name" => $ldapUser->getDepartment(),
          "#" => $this->normalizeLdapUser($ldapUser)
        ];

        return $new_array;
    }

    private function normalizeLdapUser(LdapUser $ldapUser) : array
    {
        $return_array = [
          "Unit" => []
        ];

        $phoneNumbers = $ldapUser->getLdapPhoneNumbers();
        if (!is_null($phoneNumbers)) {
            $ipphone = $this->getLdapPhoneNumberByType($phoneNumbers, "ipphone");
            $cell = $this->getLdapPhoneNumberByType($phoneNumbers, "cell");
            $home = $this->getLdapPhoneNumberByType($phoneNumbers, "home");

            $return_array["Unit"] = [
              "@Name" => $ldapUser->getFirstName()." ".$ldapUser->getLastName(),
              "@Phone1" => $ipphone->getValue(),
              "@Phone2" => $cell->getValue(),
              "@Phone3" => $home->getValue(),
              "@default_photo" => "Resource:"
            ];
        }

        return $return_array;
    }

    protected function getLdapPhoneNumberByType($array, string $type) : LdapPhoneNumbers
    {
        foreach ($array as $key => $value) {
            if ($value instanceof LdapPhoneNumbers) {
                if ($value->getType() === $type) {
                    return $value;
                }
            } else {
                throw new \Exception("Error Processing Request", 1);
            }
        }
        $emptyEntity = new LdapPhoneNumbers();
        $emptyEntity->setValue("");
        return $emptyEntity;
    }
}
