<?php
namespace App\Service;

class XmlYealinkEncoder {
  public $xml;

  public function encodeLdapUserArrayToSymfonyXmlEncoder($array) {
    $new_array = [
        "Menu" => []
      ];
    $groups = $this->groupByDepartment($array);
    foreach ($groups as $key => $ldapUsers) {
      $new_array["Menu"][] = [
        "@Name" => $key,
        "#" => $this->adjustLdapUsers($ldapUsers)
      ];
    }

    return $new_array;
  }

  private function adjustLdapUsers($ldapUsers) {
    $return_array = [
      "Unit" => []
    ];

    foreach ($ldapUsers as $key => $singleLdapUser) {
      if(!is_null($singleLdapUser["phoneNumbers"])) {
        $ipphone = $this->getPhoneNumberByType($singleLdapUser["phoneNumbers"], "ipphone");
        $cell = $this->getPhoneNumberByType($singleLdapUser["phoneNumbers"], "cell");
        $home = $this->getPhoneNumberByType($singleLdapUser["phoneNumbers"], "home");

        $return_array["Unit"][] = [
          "@Name" => $singleLdapUser["first_name"]." ".$singleLdapUser["last_name"],
          "@Phone1" => $ipphone["value"],
          "@Phone2" => $cell["value"],
          "@Phone3" => $home["value"],
          "@default_photo" => "Resource:"
        ];
      }

    }

    return $return_array;
  }

  /**
   * Creates Yealink Remote Phone book xml structure and saves it to $this->xml
   * @param array $array [Encoded LdapUser entity array]
   */
  public function create(array $array) : void {
    $string = '<?xml version="1.0" encoding="utf-8" ?> <YeastarIPPhoneBook>';

    $groups = $this->groupByDepartment($array);

    foreach ($groups as $key => $entries) {
      $string .= $this->createMenuEntry($key, $entries);
    }

    $string .= "</YeastarIPPhoneBook>";
    $this->xml = $string;
  }

  /**
   * Creates an <Menu> element and the <Unit> elements that are inside.
   * @param  string $name  The <Menu> name
   * @param  array  $array The <Unit> data inside the Menu
   * @return string        returns single <Menu><Unit/><Unit/>...</Menu>
   */
  private function createMenuEntry(string $name, array $array) : string {
    $string = "";
    $string .= '<Menu Name="'.$name.'">';

    foreach ($array as $menu_key => $unit) {
        $string .= $this->createUnitEntry($unit);
    }

    $string .= "</Menu>";

    return $string;
  }

  /**
   * Creates an <Unit> element
   * @param  array  $unit <Unit> element data
   * @return string       returns single <Unit/>
   */
  private function createUnitEntry(array $unit) : string {
    $name = $unit["first_name"]." ".$unit["last_name"];

    $ipphone = $this->getPhoneNumberByType($unit["phoneNumbers"], "ipphone");
    $cell = $this->getPhoneNumberByType($unit["phoneNumbers"], "cell");
    $home = $this->getPhoneNumberByType($unit["phoneNumbers"], "home");

    $string = '<Unit Name="'.$name.'" Phone1="'.$ipphone["value"].'" Phone2="'.$cell["value"].'" Phone3="'.$home["value"].'" default_photo="Resource:" />';

    return $string;
  }
  /**
   * Groups the given $array of encoded LdapUser by department key
   * @param  array $array Encoded LdapUser array
   * @return array        returns LdapUsers grouped by department
   */
  private function groupByDepartment(array $array) : array {
    $groups = [];

    foreach ($array as $key => $value) {
      if(!is_null($value["department"]) && strlen($value["department"]) > 0) {
        $groups[$value["department"]][] = $value;
      } else {
        $groups["Unasigned"][] = $value;
      }
    }

    return $groups;
  }

  /**
   * Retrievs the encoded PhoneNumber from array of numbers by given type
   * @param  array  $array normalized PhoneNumber array
   * @param  string $type  the searched type
   * @return array         returns the searched value or an array with value key as empty string
   */
  protected function getPhoneNumberByType(array $array, string $type) : array {
    foreach ($array as $key => $value) {
      if($value["type"] === $type) {
        return $value;
      }
    }

    return ["value" => ""];
  }
}
