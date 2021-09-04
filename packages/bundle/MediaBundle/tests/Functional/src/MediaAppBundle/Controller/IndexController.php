<?php

declare(strict_types=1);

namespace MediaAppBundle\Controller;

use MediaAppBundle\Form\Type\AuthorForm;
use MediaAppBundle\Form\Type\AuthorRequiredForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Talav\Component\Resource\Manager\ManagerInterface;

class IndexController extends AbstractController
{
    /** @var ManagerInterface */
    private $authorManager;

    public function __construct(
        ManagerInterface $authorManager
    ) {
        $this->authorManager = $authorManager;
    }

    /**
     * @Route("/test1", name="talav_media_test1")
     */
    public function test1(Request $request): Response
    {
        $form = $this->createForm(AuthorForm::class, $this->authorManager->create());
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $this->authorManager->add($form->getData());
                $this->authorManager->flush();
                $this->addFlash('success', 'Test passed');

                return new RedirectResponse($this->container->get('router')->generate('talav_media_test1'));
            }
        }

        return $this->render('@MediaApp/test.html.twig', [
            'form' => $form->createView(),
            'action' => 'talav_media_test1',
        ]);
    }

    /**
     * @Route("/test2", name="talav_media_test2")
     */
    public function test2(Request $request): Response
    {
        $form = $this->createForm(AuthorRequiredForm::class, $this->authorManager->create());
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $this->authorManager->add($form->getData());
                $this->authorManager->flush();
                $this->addFlash('success', 'Test passed');

                return new RedirectResponse($this->container->get('router')->generate('talav_media_test2'));
            }
        }

        return $this->render('@MediaApp/test.html.twig', [
            'form' => $form->createView(),
            'action' => 'talav_media_test2',
        ]);
    }
}
