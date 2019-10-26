<?php

declare(strict_types=1);

namespace Talav\Component\User\Repository;

use Talav\Component\Resource\Repository\RepositoryInterface;
use Talav\Component\User\Model\UserInterface;

interface UserRepositoryInterface extends RepositoryInterface
{
    /**
     * Find a user by its username.
     *
     * @param string $username
     */
    public function findOneByUsername($username): ?UserInterface;

    /**
     * Finds a user by its email.
     *
     * @param string $email
     */
    public function findOneByEmail($email): ?UserInterface;
}
