<?php
namespace App\Serializer;

use App\Service\VcfFactory;
use JeroenDesloovere\VCard\VCard;

use App\Exception\LdapUserNotVisibleException;

use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Serializer\Encoder\EncoderInterface;

class VcardEncoder implements EncoderInterface, DecoderInterface
{

    private $factory;

    public function __construct(VcfFactory $factory)
    {
      $this->factory = $factory;
    }

    public function encode($data, $format, array $context = [])
    {
        $factory = $this->factory;
        $this->factory->create($data, $context["operation_type"]);

        if($context["operation_type"] === "collection") {
          if(!is_null($this->factory->vcf)) {
            $headers = [
                'Content-type: ' . "text/x-vcard; charset=utf-8",
                'Content-Disposition: ' . "attachment; filename=ldapusers.vcf",
                'Content-Length: ' . mb_strlen($this->factory->vcf_output, '8bit'),
                'Connection: ' . "close",
            ];

            foreach ($headers as $header) {
              header($header);
            }

            return $this->factory->vcf_output;
          } else {
            throw new LdapUserNotVisibleException("Can't create VCard from this collection.", 1);
          }
        } else {
          if($this->factory->vcf instanceof VCar) {
            $this->factory->vcf->download();
          } else {
            throw new LdapUserNotVisibleException("Can't create VCard from this item.", 1);
          }
        }
    }

    public function supportsEncoding($format, array $context = [])
    {
        return 'vcard' === $format;
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
