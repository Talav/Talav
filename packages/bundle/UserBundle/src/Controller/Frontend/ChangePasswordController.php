<?php

declare(strict_types=1);

namespace Talav\UserBundle\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Talav\Component\User\Manager\UserManagerInterface;
use Talav\Component\User\Message\Command\UpdatePasswordCommand;
use Talav\UserBundle\Event\TalavUserEvents;
use Talav\UserBundle\Event\UserEvent;
use Talav\UserBundle\Form\Model\ChangePasswordModel;
use Talav\UserBundle\Form\Type\ChangePasswordType;

/**
 * @Route("/user")
 */
class ChangePasswordController extends AbstractController
{
    public function __construct(
        private EventDispatcherInterface $eventDispatcher,
        private UserManagerInterface $userManager,
        private TranslatorInterface $translator,
        private MessageBusInterface $messageBus,
    ) {
    }

    /**
     * @Route("/change-password", name="talav_user_change_password")
     */
    public function changePassword(Request $request): Response
    {
        $form = $this->createForm(ChangePasswordType::class, new ChangePasswordModel());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $this->messageBus->dispatch(new UpdatePasswordCommand($user, $form->getData()->newPassword));
            $this->userManager->update($user, true);
            $this->eventDispatcher->dispatch(new UserEvent($user), TalavUserEvents::CHANGE_PASSWORD_SUCCESS);
            $this->addFlash(
                'success',
                $this->translator->trans('talav.change_password.flash.success', [], 'TalavUserBundle')
            );

            return new RedirectResponse($this->container->get('router')->generate('talav_user_login'));
        }

        return $this->render('@TalavUser/frontend/change-password/change_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
