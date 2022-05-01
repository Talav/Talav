<?php

declare(strict_types=1);

namespace Talav\AvatarBundle\Controller\Frontend;

use AutoMapperPlus\AutoMapperInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Talav\Component\User\Manager\UserManagerInterface;
use Talav\MediaBundle\Form\Model\MediaModel;
use Talav\MediaBundle\Form\Type\MediaType;
use Talav\MediaBundle\Message\Command\ProcessMediaModelCommand;
use Talav\MediaBundle\Message\Dto\ProcessMediaDto;

class AvatarController extends AbstractController
{
    private UserManagerInterface $userManager;

    private TranslatorInterface $translator;

    private AutoMapperInterface $autoMapper;

    private MessageBusInterface $bus;

    public function __construct(
        UserManagerInterface $userManager,
        TranslatorInterface $translator,
        AutoMapperInterface $autoMapper,
        MessageBusInterface $bus
    ) {
        $this->userManager = $userManager;
        $this->translator = $translator;
        $this->autoMapper = $autoMapper;
        $this->bus = $bus;
    }

    /**
     * @Route("/user/profile/avatar", name="talav_user_profile_avatar")
     */
    public function updateAvatar(Request $request): Response
    {
        $user = $this->getUser();
        $model = is_null($user->getAvatar()) ? new MediaModel() : $this->autoMapper->map($user->getAvatar(), MediaModel::class);

        $form = $this->createForm(MediaType::class, $model, [
            'provider' => 'image',
            'context' => 'avatar',
            'required' => false,
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $dto = ProcessMediaDto::fromObjects($user->getAvatar(), $form->getData());
            $dto->name = $user->getAvatarName();
            $dto->description = $user->getAvatarDescription();
            $media = $this->bus->dispatch(new ProcessMediaModelCommand($dto))->last(HandledStamp::class)->getResult();
            $user->setAvatar($media);
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
