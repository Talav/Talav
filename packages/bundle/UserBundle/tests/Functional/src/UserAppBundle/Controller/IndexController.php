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
     * @Route("/registration-succcess", name="test_talav_user_registration_success")
     */
    public function register(Request $request): Response
    {
        return $this->render('@UserApp/index/registration-success.html.twig');
    }
}
