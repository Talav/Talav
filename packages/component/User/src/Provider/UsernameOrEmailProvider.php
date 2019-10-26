<?php

declare(strict_types=1);

namespace Talav\Component\User\Provider;

use Symfony\Component\Security\Core\User\UserInterface;

class UsernameOrEmailProvider extends AbstractUserProvider
{
    /**
     * {@inheritdoc}
     */
    protected function findUser(string $usernameOrEmail): ?UserInterface
    {
        return $this->userManager->findUserByUsernameOrEmail($usernameOrEmail);
    }
}
