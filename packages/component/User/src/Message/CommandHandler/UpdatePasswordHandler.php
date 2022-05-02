<?php

declare(strict_types=1);

namespace Talav\Component\User\Message\CommandHandler;

use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Talav\Component\User\Manager\UserManagerInterface;
use Talav\Component\User\Message\Command\UpdatePasswordCommand;

final class UpdatePasswordHandler implements MessageHandlerInterface
{
    public function __construct(
        private UserManagerInterface $userManager,
        private UserPasswordHasherInterface $userPasswordHasher
    ) {
    }

    public function __invoke(UpdatePasswordCommand $message)
    {
        $user = $message->user;
        $user->setPassword($this->userPasswordHasher->hashPassword($user, $message->password));
        $this->userManager->update($user, true);

        return $user;
    }
}
