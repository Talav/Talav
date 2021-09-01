<?php

declare(strict_types=1);

namespace Talav\UserBundle\Controller\Frontend;

use DateInterval;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Talav\Component\User\Manager\UserManagerInterface;
use Talav\Component\User\Model\UserInterface;
use Talav\UserBundle\Event\TalavUserEvents;
use Talav\UserBundle\Event\UserEvent;
use Talav\UserBundle\Form\Model\PasswordResetModel;
use Talav\UserBundle\Form\Model\RequestPasswordResetModel;
use Talav\UserBundle\Form\Type\PasswordResetType;
use Talav\UserBundle\Form\Type\RequestPasswordResetType;
use Talav\UserBundle\Mailer\UserMailerInterface;

/**
 * Controller managing the resetting of the password.
 */
class ResettingController extends AbstractController
{
    private EventDispatcherInterface $eventDispatcher;

    private UserManagerInterface $userManager;

    private TokenGeneratorInterface $tokenGenerator;

    private TranslatorInterface $translator;

    private UserMailerInterface $mailer;

    private string $retryTtl;

    private string $tokenTtl;

    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        UserManagerInterface $userManager,
        TokenGeneratorInterface $tokenGenerator,
        TranslatorInterface $translator,
        UserMailerInterface $mailer,
        string $retryTtl,
        string $tokenTtl
    ) {
        $this->eventDispatcher = $eventDispatcher;
        $this->userManager = $userManager;
        $this->tokenGenerator = $tokenGenerator;
        $this->translator = $translator;
        $this->mailer = $mailer;
        $this->retryTtl = $retryTtl;
        $this->tokenTtl = $tokenTtl;
    }

    /**
     * Request reset user password.
     *
     * @Route("/reset", name="talav_user_reset_request")
     *
     * @throws Exception
     */
    public function request(Request $request): Response
    {
        $form = $this->createForm(RequestPasswordResetType::class, new RequestPasswordResetModel());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UserInterface $user */
            $user = $form->getData()->getUser();
            if (null !== $user->getPasswordRequestedAt() && $user->isPasswordRequestNonExpired(
                DateInterval::createFromDateString($this->retryTtl)
            )) {
                $this->addFlash(
                    'error',
                    $this->translator->trans('talav.reset.flash.token_too_often', [], 'TalavUserBundle')
                );

                return new RedirectResponse($this->generateUrl('talav_user_login'));
            }
            if (null === $user->getPasswordResetToken()) {
                $user->setPasswordResetToken($this->tokenGenerator->generateToken());
            }
            $user->setPasswordRequestedAt(new \DateTime());
            $this->userManager->update($user, true);
            $this->eventDispatcher->dispatch(new UserEvent($user), TalavUserEvents::RESET_REQUEST_SUCCESS);
            $this->mailer->sendResettingEmailMessage($user);
            $this->addFlash(
                'success',
                $this->translator->trans('talav.reset.flash.success_request', [], 'TalavUserBundle')
            );

            return new RedirectResponse($this->generateUrl('talav_user_reset_request'));
        }

        return $this->render('@TalavUser/frontend/resetting/request.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Reset user password.
     *
     * @Route("/reset/{token}", name="talav_user_reset_password")
     */
    public function resetAction(Request $request, string $token): Response
    {
        $user = $this->userManager->getRepository()->findOneBy(['passwordResetToken' => $token]);
        if (null === $user) {
            $this->addFlash(
                'error',
                $this->translator->trans('talav.reset.flash.token_not_found', [], 'TalavUserBundle')
            );

            return new RedirectResponse($this->container->get('router')->generate('talav_user_login'));
        }
        if (!$user->isPasswordRequestNonExpired(DateInterval::createFromDateString($this->tokenTtl))) {
            $this->addFlash(
                'error',
                $this->translator->trans('talav.reset.flash.token_expired', [], 'TalavUserBundle')
            );

            return new RedirectResponse($this->container->get('router')->generate('talav_user_reset_request'));
        }
        $form = $this->createForm(PasswordResetType::class, new PasswordResetModel());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $model = $form->getData();
            $user->setPlainPassword($model->getPlainPassword());
            $user->setPasswordResetToken(null);
            $user->setPasswordRequestedAt(null);
            $this->userManager->update($user, true);
            $this->addFlash('success', $this->translator->trans('talav.reset.flash.success', [], 'TalavUserBundle'));

            return new RedirectResponse($this->container->get('router')->generate('talav_user_login'));
        }

        return $this->render('@TalavUser/frontend/resetting/reset.html.twig', [
            'form' => $form->createView(),
            'token' => $token,
        ]);
    }
}
