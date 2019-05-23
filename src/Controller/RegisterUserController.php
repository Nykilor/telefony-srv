<?php
namespace App\Controller;

use App\Entity\User;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterUserController
{
    public function __invoke(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $parametersAsArray = [];
        if ($content = $request->getContent()) {
            $parametersAsArray = json_decode($content, true);
        } else {
            $parametersAsArray = $this->request->all();
        }

        $username = $parametersAsArray["username"];
        $password = $parametersAsArray["password"];

        $user = new User();
        $user->setUsername($username);
        $user->setPassword($encoder->encodePassword($user, $password));

        return $user;
    }
}
