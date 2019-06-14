<?php
// src/Serializer/AdminContextBuilder.php
namespace App\Serializer;

use ApiPlatform\Core\Serializer\SerializerContextBuilderInterface;
use App\Entity\LdapUser;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Based on https://www.thinkbean.com/drupal-development-blog/api-platform-dynamically-restrict-properties-based-user
 */
final class AdminContextBuilder implements SerializerContextBuilderInterface
{
    private $decorated;
    private $authorizationChecker;

    public function __construct(SerializerContextBuilderInterface $decorated, AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->decorated = $decorated;
        $this->authorizationChecker = $authorizationChecker;
    }

    public function createFromRequest(Request $request, bool $normalization, ?array $extractedAttributes = null): array
    {
        $context = $this->decorated->createFromRequest($request, $normalization, $extractedAttributes);
        // Add `admin:read` for normalization requests
        // Otherwise, add `admin:write` for denormalization requests
        $resourceClass = $context["resource_class"] ?? null;

        if ($this->authorizationChecker->isGranted('ROLE_ADMIN') && $resourceClass === LdapUser::class) {
            $context['groups'][] = $normalization ? 'admin:read' : 'admin:write';
        }

        return $context;
    }
}
