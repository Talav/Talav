<?php

declare(strict_types=1);

namespace MediaAppBundle\Controller;

use MediaAppBundle\Entity\Author;
use MediaAppBundle\Form\Model\EntityModel;
use MediaAppBundle\Form\Type\AuthorForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Talav\Component\Resource\Manager\ManagerInterface;
use Talav\Component\User\Manager\UserManagerInterface;

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
     * @Route("/test", name="talav_media_test")
     */
    public function register(Request $request): Response
    {
        $form = $this->createForm(AuthorForm::class, $this->authorManager->create());
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $this->authorManager->add($form->getData());
                $this->authorManager->flush();
                $this->addFlash('success', "Test passed");
                return new RedirectResponse($this->container->get('router')->generate('talav_media_test'));
            }
        }

        return $this->render('@MediaApp/test.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
