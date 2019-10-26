<?php

declare(strict_types=1);

namespace Talav\Component\User\Provider;

use Symfony\Component\Security\Core\User\UserInterface;

class EmailProvider extends AbstractUserProvider
{
    /**
     * {@inheritdoc}
     */
    protected function findUser(string $email): ?UserInterface
    {
        return $this->userManager->findUserByEmail($email);
    }
}
