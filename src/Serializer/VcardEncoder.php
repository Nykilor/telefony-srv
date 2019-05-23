<?php
namespace App\Serializer;

use App\Service\VcardVcfEncoder;
use JeroenDesloovere\VCard\VCard;

use App\Exception\LdapUserNotVisibleException;

use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Serializer\Encoder\EncoderInterface;

class VcardEncoder implements EncoderInterface, DecoderInterface
{
    private $encoder;

    public function __construct(VcardVcfEncoder $encoder)
    {
        $this->encoder = $encoder;
    }

    public function encode($data, $format, array $context = [])
    {
        $this->encoder->encode($data, $context["operation_type"]);

        if ($context["operation_type"] === "collection") {
            if (!is_null($this->encoder->vcf)) {
                $headers = [
                  'Content-type: ' . "text/x-vcard; charset=utf-8",
                  'Content-Disposition: ' . "attachment; filename=ldapusers.vcf",
                  'Content-Length: ' . mb_strlen($this->encoder->vcf_output, '8bit'),
                  'Connection: ' . "close",
                ];

                foreach ($headers as $header) {
                    header($header);
                }

                return $this->encoder->vcf_output;
            } else {
                throw new LdapUserNotVisibleException("Can't create VCard from this collection.", 1);
            }
        } else {
            if ($this->encoder->vcf instanceof VCard) {
                $this->encoder->vcf->download();
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
