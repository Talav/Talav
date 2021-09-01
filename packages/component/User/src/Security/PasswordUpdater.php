<?php

declare(strict_types=1);

namespace Talav\Component\User\Security;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Talav\Component\User\Model\CredentialsHolderInterface;

class PasswordUpdater implements PasswordUpdaterInterface
{
    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function updatePassword(CredentialsHolderInterface $user): void
    {
        if (!empty($user->getPlainPassword())) {
            $user->setPassword($this->userPasswordHasher->hashPassword($user, $user->getPlainPassword()));
            $user->eraseCredentials();
        }
    }
}
