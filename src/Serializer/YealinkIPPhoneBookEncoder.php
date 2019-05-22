<?php
namespace App\Serializer;

use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Serializer\Encoder\EncoderInterface;

class YealinkIPPhoneBookEncoder implements EncoderInterface, DecoderInterface
{

    public function encode($data, $format, array $context = [])
    {
      $groups = [];
      foreach ($data as $group => $value) {
        if(!array_key_exists($value["@Name"], $groups)) {
          $groups[$value["@Name"]] = [];
        }

        $groups[$value["@Name"]][] = $value["#"];
      }

      $result = [
        "Menu" => []
      ];
      foreach ($groups as $key => $unitArray) {
        foreach ($unitArray as $key => $value) {
          //TODO 
        }
      }
      var_dump($groups);
      exit();
      $new_array = array_merge_recursive($data[0], $data[1]);
      $xml_encoder = new XmlEncoder();
      $result = $xml_encoder->encode($new_array, "xml", [
        "xml_encoding" => "utf-8",
        "xml_root_node_name" => "YealinkIPPhoneBook"
      ]);
      return $result;
    }

    public function supportsEncoding($format, array $context = [])
    {
        return 'YealinkIPPhoneBook' === $format;
    }

    public function decode($data, $format, array $context = [])
    {
        return false;
    }

    public function supportsDecoding($format, array $context = [])
    {
        return false;
    }
}
