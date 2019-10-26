<?php

declare(strict_types=1);

namespace Talav\Component\User\Repository;

use Talav\Component\Resource\Repository\ResourceRepository;
use Talav\Component\User\Model\UserInterface;

class UserRepository extends ResourceRepository implements UserRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function findOneByUsername($username): ?UserInterface
    {
        return $this->findOneBy(['usernameCanonical' => $username]);
    }

    /**
     * {@inheritdoc}
     */
    public function findOneByEmail($email): ?UserInterface
    {
        return $this->findOneBy(['emailCanonical' => $email]);
    }
}
