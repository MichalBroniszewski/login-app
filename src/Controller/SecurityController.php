<?php

/**
 * user: michal
 * michal.broniszewski@picodi.com
 * 25.10.2020
 */
declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login/{username}", name="security_login", defaults={"username"=null})
     * @param AuthenticationUtils $authenticationUtils
     * @param string|null $username
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils, ?string $username): Response
    {
        return $this->render(
            'security/login.html.twig',
            [
                'last_username' => $username ?: $authenticationUtils->getLastUsername(),
                'error' => $authenticationUtils->getLastAuthenticationError()
            ]
        );
    }

    /**
     * @Route("/logout", name="security_logout")
     */
    public function logout(): void
    {

    }
}
