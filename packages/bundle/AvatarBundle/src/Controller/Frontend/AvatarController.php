<?php

declare(strict_types=1);

namespace Talav\AvatarBundle\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Talav\AvatarBundle\Form\Type\AvatarType;
use Talav\Component\User\Manager\UserManagerInterface;

class AvatarController extends AbstractController
{
    private UserManagerInterface $userManager;

    private TranslatorInterface $translator;

    public function __construct(
        UserManagerInterface $userManager,
        TranslatorInterface $translator
    ) {
        $this->userManager = $userManager;
        $this->translator = $translator;
    }

    /**
     * @Route("/user/profile/avatar", name="talav_user_profile_avatar")
     */
    public function updateAvatar(Request $request)
    {
        $form = $this->createForm(AvatarType::class, $this->getUser(), [
            'provider' => 'image',
            'context' => 'avatar',
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $this->userManager->update($user, true);
            $this->addFlash(
                'success',
                $this->translator->trans('talav.update_avatar.flash.success', [], 'TalavAvatarBundle')
            );

            return $this->redirectToRoute('talav_user_profile_avatar');
        }

        return $this->render('@TalavAvatar/frontend/avatar/update_avatar.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
