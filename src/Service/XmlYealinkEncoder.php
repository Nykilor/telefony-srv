<?php
namespace App\Service;

use Symfony\Component\Serializer\Encoder\XmlEncoder;

class XmlYealinkEncoder
{

  /**
   * Encodes the array given by XmlYealinkNormalizer
   * @param  array  $data Normalized LdapUser Entity by XmlYealinkNormalizer
   * @return string The xml structure
   */
    public function encode(array $data) : string
    {
        $groups = $this->groupByDepartment($data);

        $menu_array = [
          "Menu" => []
        ];

        $menu_array["Menu"] = $this->createStructureForXmlEncoder($groups);

        return $this->getEncodedData($menu_array);
    }

    /**
     * Creates the xml structure from given array
     * @param  array  $data Normalized LdapUser
     * @return string       The xml structure
     */
    private function getEncodedData(array $data) : string
    {
        $xml_encoder = new XmlEncoder();
        $result = $xml_encoder->encode($data, "xml", [
          "xml_encoding" => "utf-8",
          "xml_root_node_name" => "YealinkIPPhoneBook"
        ]);

        return $result;
    }


    /**
     * Adjusts the Normalized array for Symfony XmlEncoder
     * @param  array  $groups The LdapUser array grouped by Department
     * @return array          Structure for XmlEncoder
     */
    private function createStructureForXmlEncoder(array $groups) : array
    {
        $replace_menu = [];
        foreach ($groups as $key => $unitArray) {
            $add_to_replace = [];
            $add_to_replace["@Name"] = $key;
            foreach ($unitArray as $key => $value) {
                $filledUnit = $this->removeEmptyUnit($value["Unit"]);
                if (!is_null($filledUnit)) {
                    $add_to_replace["Unit"][] = $value["Unit"];
                }
            }
            $replace_menu[] = $add_to_replace;
        }

        return $replace_menu;
    }

    /**
     * Groups the given array by @Name key
     * @param  array $data Normalized LdapUser entity by XmlYealinkNormalizer
     * @return array       $data array but grouped by @Name
     */
    private function groupByDepartment(array $data) : array
    {
        $groups = [];
        foreach ($data as $group => $value) {
            if (!array_key_exists($value["@Name"], $groups)) {
                $groups[$value["@Name"]] = [];
            }

            $groups[$value["@Name"]][] = $value["#"];
        }

        return $groups;
    }

    /**
     * Returns back the given value or null if the given array dosn't have at least one of the values
     * @param  array  $unit One of normalized single entity data
     * @return array|null
     */
    private function removeEmptyUnit(array $unit) : ?array
    {
        if (empty($unit["@Phone1"]) && empty($unit["@Phone2"]) && empty($nuit["@Phone3"])) {
            return null;
        } else {
            return $unit;
        }
    }
}
