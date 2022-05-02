<?php

declare(strict_types=1);

namespace Talav\Component\User\Message\CommandHandler;

use AutoMapperPlus\AutoMapperInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Talav\Component\User\Manager\UserManagerInterface;
use Talav\Component\User\Message\Command\CreateUserCommand;
use Talav\Component\User\Message\Event\NewUserEvent;

final class CreateUserHandler implements MessageHandlerInterface
{
    public function __construct(
        private AutoMapperInterface $mapper,
        private UserManagerInterface $userManager,
        private MessageBusInterface $messageBus,
        private UserPasswordHasherInterface $userPasswordHasher
    ) {
    }

    public function __invoke(CreateUserCommand $message)
    {
        $user = $this->mapper->mapToObject($message->dto, $this->userManager->create());
        $user->setPassword($this->userPasswordHasher->hashPassword($user, $message->dto->password));
        $this->userManager->update($user, true);

        $this->messageBus->dispatch(new NewUserEvent($user->getId()));

        return $user;
    }
}
