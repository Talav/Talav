<?php

declare(strict_types=1);

namespace UserAppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/user/profile", name="test_talav_user_profile")
     */
    public function profile(Request $request): Response
    {
        return $this->render('@UserApp/index/profile.html.twig');
    }

    /**
     * @Route("/login-success", name="test_talav_user_login_success")
     */
    public function loginSuccess(Request $request): Response
    {
        return $this->render('@UserApp/index/login-success.html.twig');
    }
}
