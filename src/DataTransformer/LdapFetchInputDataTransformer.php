<?php
// src/DataTransformer/BookInputDataTransformer.php

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use ApiPlatform\Core\Serializer\AbstractItemNormalizer;
use App\Entity\Domain;

final class LdapFetchInputDataTransformer implements DataTransformerInterface
{
    /**
     * {@inheritdoc}
     */
    public function transform($data, string $to, array $context = [])
    {
        $entity = $data;
        $entity->domain = $context[AbstractItemNormalizer::OBJECT_TO_POPULATE];

        return $entity;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        if ($data instanceof Domain) {
            return false;
        }

        return Domain::class === $to && null !== ($context['input']['class'] ?? null);
    }
}
