<?php

declare(strict_types=1);

namespace Talav\UserBundle\Controller\Frontend;

use AutoMapperPlus\AutoMapperInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Talav\Component\User\Manager\UserManagerInterface;
use Talav\Component\User\Message\Command\CreateUserCommand;
use Talav\Component\User\Message\Dto\CreateUserDto;
use Talav\UserBundle\Event\TalavUserEvents;
use Talav\UserBundle\Event\UserEvent;
use Talav\UserBundle\Security\LoginFormAuthenticator;

class RegistrationController extends AbstractController
{
    private UserManagerInterface $userManager;

    private LoginFormAuthenticator $formLoginAuthenticator;

    private UserAuthenticatorInterface $userAuthenticator;

    private EventDispatcherInterface $eventDispatcher;

    private AutoMapperInterface $mapper;

    private MessageBusInterface $bus;

    private iterable $parameters = [];

    public function __construct(
        UserManagerInterface $userManager,
        LoginFormAuthenticator $formLoginAuthenticator,
        UserAuthenticatorInterface $userAuthenticator,
        EventDispatcherInterface $eventDispatcher,
        AutoMapperInterface $mapper,
        MessageBusInterface $bus,
        array $parameters
    ) {
        $this->userManager = $userManager;
        $this->formLoginAuthenticator = $formLoginAuthenticator;
        $this->userAuthenticator = $userAuthenticator;
        $this->eventDispatcher = $eventDispatcher;
        $this->mapper = $mapper;
        $this->bus = $bus;
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
                $dto = $this->mapper->map($form->getData(), CreateUserDto::class);
                $user = $this->bus->dispatch(new CreateUserCommand($dto))->last(HandledStamp::class)->getResult();

                $this->eventDispatcher->dispatch(new UserEvent($user), TalavUserEvents::REGISTRATION_COMPLETED);

                return $this->userAuthenticator->authenticateUser(
                    $user,
                    $this->formLoginAuthenticator,
                    $request
                );
            }
        }

        return $this->render('@TalavUser/frontend/registration/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
