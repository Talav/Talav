<?php

declare(strict_types=1);

namespace Talav\UserBundle\Controller\Frontend;

use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="talav_user_login")
     */
    public function login(Request $request): Response
    {
        // get the login error if there is one
        $error = $this->get('authentication.utils')->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $this->get('authentication.utils')->getLastUsername();

        return $this->render('@TalavUser/frontend/security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    /**
     * Logout action. This action should never be called.
     *
     * @Route("/logout", name="talav_user_logout", methods={"GET"})
     */
    public function logout(): Response
    {
        throw new RuntimeException('You must configure the logout path to be handled by the firewall.');
    }

    public static function getSubscribedServices(): array
    {
        return parent::getSubscribedServices() + [
                'authentication.utils' => AuthenticationUtils::class,
            ];
    }
}
