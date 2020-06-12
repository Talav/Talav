<?php

declare(strict_types=1);

namespace Talav\UserBundle\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Talav\Component\User\Manager\UserManagerInterface;
use Talav\UserBundle\Event\TalavUserEvents;
use Talav\UserBundle\Event\UserEvent;
use Talav\UserBundle\Form\Model\RegistrationFormModel;
use Talav\UserBundle\Form\Type\RegistrationFormType;
use Talav\UserBundle\Security\LoginFormAuthenticator;

class RegistrationController extends AbstractController
{
    use TargetPathTrait;

    /** @var UserManagerInterface */
    private $userManager;

    /** @var LoginFormAuthenticator */
    private $authenticator;

    /** @var GuardAuthenticatorHandler */
    private $guard;

    /** @var EventDispatcherInterface */
    private $eventDispatcher;

    public function __construct(
        UserManagerInterface $userManager,
        LoginFormAuthenticator $authenticator,
        GuardAuthenticatorHandler $guard,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->userManager = $userManager;
        $this->authenticator = $authenticator;
        $this->guard = $guard;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @Route("/register", name="talav_user_register")
     *
     * @return array
     */
    public function register(Request $request)
    {
        /** @var UserManagerInterface $userManager */
        $form = $this->createForm(RegistrationFormType::class, new RegistrationFormModel());
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $user = $this->userManager->create();
                $user->setUsername($form->getData()->getUsername());
                $user->setEmail($form->getData()->getEmail());
                $user->setPlainPassword($form->getData()->getPlainPassword());
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
