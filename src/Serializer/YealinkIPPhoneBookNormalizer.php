<?php
namespace App\Serializer;

use App\Entity\LdapUser;
use App\Service\XmlYealinkNormalizer;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class YealinkIPPhoneBookNormalizer implements NormalizerInterface
{
    private $router;
    private $normalizer;

    public function __construct(UrlGeneratorInterface $router, XmlYealinkNormalizer $normalizer)
    {
        $this->router = $router;
        $this->normalizer = $normalizer;
    }

    public function normalize($topic, $format = null, array $context = [])
    {
        $data = $this->normalizer->normalize($topic);
        // Here, add, edit, or delete some data:
        return $data;
    }

    public function supportsNormalization($data, $format = null, array $context = [])
    {
        return $data instanceof LdapUser && $format === "YealinkIPPhoneBook";
    }
}
