<?php

declare(strict_types=1);

namespace Talav\UserBundle\Controller\Frontend;

use AutoMapperPlus\AutoMapperInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Talav\Component\User\Manager\UserManagerInterface;
use Talav\UserBundle\Event\TalavUserEvents;
use Talav\UserBundle\Event\UserEvent;
use Talav\UserBundle\Security\LoginFormAuthenticator;

class RegistrationController extends AbstractController
{
    private UserManagerInterface $userManager;

    private LoginFormAuthenticator $authenticator;

    private GuardAuthenticatorHandler $guard;

    private EventDispatcherInterface $eventDispatcher;

    private AutoMapperInterface $mapper;

    private iterable $parameters = [];

    public function __construct(
        UserManagerInterface $userManager,
        LoginFormAuthenticator $authenticator,
        GuardAuthenticatorHandler $guard,
        EventDispatcherInterface $eventDispatcher,
        AutoMapperInterface $mapper,
        array $parameters
    ) {
        $this->userManager = $userManager;
        $this->authenticator = $authenticator;
        $this->guard = $guard;
        $this->eventDispatcher = $eventDispatcher;
        $this->mapper = $mapper;
        $this->parameters = $parameters;
    }

    /**
     * @Route("/register", name="talav_user_register")
     */
    public function register(Request $request): Response
    {
        $form = $this->createForm(
            $this->parameters['form_type'],
            new $this->parameters['form_model'](),
            ['validation_groups' => $this->parameters['form_validation_groups']]
        );
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $user = $this->userManager->create();
                $this->mapper->mapToObject($form->getData(), $user);
                $this->userManager->update($user, true);
                $this->eventDispatcher->dispatch(new UserEvent($user), TalavUserEvents::REGISTRATION_COMPLETED);

                return $this->guard->authenticateUserAndHandleSuccess(
                    $user,
                    $request,
                    $this->authenticator,
                    'main'
                );
            }
        }

        return $this->render('@TalavUser/frontend/registration/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
