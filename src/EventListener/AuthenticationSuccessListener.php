<?php
namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;

use Symfony\Component\Security\Core\User\UserInterface;
/**
 * Authentication success class for JWT to add public data to the response.
 */
class AuthenticationSuccessListener
{

  public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event)
  {
    $data = $event->getData();
    $user = $event->getUser();

    if (!$user instanceof UserInterface) {
        return;
    }

    $data["data"] = array(
        "id" => $user->getId(),
        "roles" => $user->getRoles(),
        "username" => $user->getUsername()
    );

    $event->setData($data);
  }
}
