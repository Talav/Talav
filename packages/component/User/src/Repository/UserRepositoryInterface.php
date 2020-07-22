<?php

declare(strict_types=1);

namespace Talav\Component\User\Repository;

use Talav\Component\Resource\Repository\RepositoryInterface;
use Talav\Component\User\Model\UserInterface;

interface UserRepositoryInterface extends RepositoryInterface
{
    /**
     * Find a user by its username.
     */
    public function findOneByUsername(string $username): ?UserInterface;

    /**
     * Finds a user by its email.
     */
    public function findOneByEmail(string $email): ?UserInterface;
}
