<?php
namespace App\Serializer;

use App\Service\VcfFactory;

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
        if(is_array($data)) {
          $this->factory->create($data);
          $headers = [
              'Content-type' => "text/x-vcard; charset=utf-8",
              'Content-Disposition' => "attachment; filename=ldapusers.vcf",
              'Content-Length' => mb_strlen($this->factory->vcf_output, '8bit'),
              'Connection' => "close",
          ];

          return $this->factory->vcf_output;
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
