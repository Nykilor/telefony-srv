<?php
namespace App\Serializer;

use App\Service\XmlYealinkEncoder;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\HeaderUtils;

use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Serializer\Encoder\EncoderInterface;

class YealinkIPPhoneBookEncoder implements EncoderInterface, DecoderInterface
{
    public $encoder;

    public function __construct(XmlYealinkEncoder $encoder)
    {
        $this->encoder = $encoder;
    }

    public function encode($data, $format, array $context = [])
    {
        $xml = $this->encoder->encode($data);
        $response = new Response($xml);
        $disposition = HeaderUtils::makeDisposition(
          HeaderUtils::DISPOSITION_ATTACHMENT,
          "contact.xml"
        );
        $response->headers->set('Content-Disposition', $disposition);

        return $response;
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
