<?php

declare(strict_types=1);

namespace Talav\UserBundle\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Talav\Component\User\Manager\UserManagerInterface;
use Talav\Component\User\Model\UserInterface;
use Talav\UserBundle\Event\TalavUserEvents;
use Talav\UserBundle\Event\UserEvent;
use Talav\UserBundle\Mailer\UserMailerInterface;

final class WelcomeEmailSubscriber implements EventSubscriberInterface
{
    private UserManagerInterface $userManager;

    private UserMailerInterface $mailer;

    public function __construct(UserManagerInterface $userManager, UserMailerInterface $mailer)
    {
        $this->userManager = $userManager;
        $this->mailer = $mailer;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            TalavUserEvents::REGISTRATION_COMPLETED => 'onRegistrationComplete',
        ];
    }

    public function onRegistrationComplete(UserEvent $event): void
    {
        $user = $event->getUser();
        $supportedClass = $this->userManager->getClassName();
        if (!$user instanceof $supportedClass) {
            return;
        }
        if (!$user instanceof UserInterface) {
            throw new \UnexpectedValueException('In order to use this subscriber, your class has to implement UserInterface');
        }
        $this->mailer->sendRegistrationSuccessfulEmail($user);
    }
}
