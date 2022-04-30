<?php

declare(strict_types=1);

namespace MediaAppBundle\Controller;

use AutoMapperPlus\AutoMapperInterface;
use MediaAppBundle\Form\Model\AuthorModel;
use MediaAppBundle\Form\Type\AuthorForm;
use MediaAppBundle\Form\Type\AuthorRequiredForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Annotation\Route;
use Talav\Component\Media\Message\Command\Media\CreateMediaCommand;
use Talav\Component\Media\Message\Dto\Media\CreateMediaDto;
use Talav\Component\Resource\Manager\ManagerInterface;
use Talav\MediaBundle\Message\Command\ProcessMediaModelCommand;
use Talav\MediaBundle\Message\Dto\ProcessMediaDto;

class IndexController extends AbstractController
{
    private ManagerInterface $authorManager;

    private AutoMapperInterface $autoMapper;

    private MessageBusInterface $bus;

    public function __construct(
        ManagerInterface $authorManager,
        AutoMapperInterface $autoMapper,
        MessageBusInterface $bus
    ) {
        $this->authorManager = $authorManager;
        $this->autoMapper = $autoMapper;
        $this->bus = $bus;
    }

    /**
     * @Route("/test1", name="talav_media_test1")
     */
    public function test1(Request $request): Response
    {
        $form = $this->createForm(AuthorForm::class, new AuthorModel());
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $author = $this->authorManager->create();
                $author->setName($form->getData()->name);

                if (isset($form->getData()->media)) {
                    $dto = $this->autoMapper->map($form->getData()->media, CreateMediaDto::class);
                    $media = $this->bus->dispatch(new CreateMediaCommand($dto))->last(HandledStamp::class)->getResult();
                    $author->setMedia($media);
                }
                $this->authorManager->update($author, true);
                $this->addFlash('success', 'Test passed');

                return new RedirectResponse($this->container->get('router')->generate('talav_media_test1'));
            }
        }

        return $this->render('@MediaApp/test_submission.html.twig', [
            'form' => $form->createView(),
            'action' => $this->generateUrl('talav_media_test1'),
        ]);
    }

    /**
     * @Route("/test2", name="talav_media_test2")
     */
    public function test2(Request $request): Response
    {
        $form = $this->createForm(AuthorRequiredForm::class, new AuthorModel());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->authorManager->add($form->getData());
            $this->authorManager->flush();
            $this->addFlash('success', 'Test passed');

            return new RedirectResponse($this->container->get('router')->generate('talav_media_test2'));
        }

        return $this->render('@MediaApp/test_submission.html.twig', [
            'form' => $form->createView(),
            'action' => $this->generateUrl('talav_media_test2'),
        ]);
    }

    /**
     * @Route("/form/test3/{id}/edit", name="talav_media_form_test3")
     */
    public function test3(int $id, Request $request): Response
    {
        $author = $this->authorManager->getRepository()->find($id);
        $model = $this->autoMapper->map($author, AuthorModel::class);

        $form = $this->createForm(AuthorForm::class, $model);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $author->setName($form->getData()->name);
            $media = $this->bus->dispatch(new ProcessMediaModelCommand(ProcessMediaDto::fromObjects($author->getMedia(), $form->getData()->media)))->last(HandledStamp::class)->getResult();
            $author->setMedia($media);
            $this->authorManager->update($author, true);
            $this->addFlash('success', 'Test passed');

            return new RedirectResponse($this->container->get('router')->generate('talav_media_form_test3', ['id' => $id]));
        }

        return $this->render('@MediaApp/test_submission.html.twig', [
            'form' => $form->createView(),
            'action' => $this->generateUrl('talav_media_form_test3', ['id' => $id]),
        ]);
    }

    /**
     * @Route("/view/test1", name="talav_media_view_test_1")
     */
    public function testView1(): Response
    {
        return $this->render('@MediaApp/view_test1.html.twig', [
            'author' => $this->authorManager->getRepository()->find(1),
        ]);
    }

    /**
     * @Route("/view/test2", name="talav_media_view_test_2")
     */
    public function testView2(): Response
    {
        return $this->render('@MediaApp/view_test2.html.twig', [
            'author' => $this->authorManager->getRepository()->find(2),
        ]);
    }

    /**
     * @Route("/view/test3", name="talav_media_view_test_3")
     */
    public function testView3(): Response
    {
        return $this->render('@MediaApp/view_test3.html.twig', [
            'author' => $this->authorManager->getRepository()->find(2),
        ]);
    }

    /**
     * @Route("/view/test4", name="talav_media_view_test_4")
     */
    public function testView4(): Response
    {
        return $this->render('@MediaApp/view_test4.html.twig', [
            'author' => $this->authorManager->getRepository()->find(2),
        ]);
    }
}
