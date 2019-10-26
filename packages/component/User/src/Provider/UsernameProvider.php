<?php

declare(strict_types=1);

namespace Talav\Component\User\Provider;

use Symfony\Component\Security\Core\User\UserInterface;

class UsernameProvider extends AbstractUserProvider
{
    /**
     * {@inheritdoc}
     */
    protected function findUser(string $username): ?UserInterface
    {
        return $this->userManager->findUserByUsername($username);
    }
}
