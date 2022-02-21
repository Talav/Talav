<?php

declare(strict_types=1);

namespace Talav\Component\User\Manager;

use Talav\Component\Resource\Manager\ManagerInterface;
use Talav\Component\User\Model\UserInterface;

/**
 * Interface to be implemented by user managers. This adds a level
 * of abstraction between your application, and the actual repository.
 *
 * All changes to users should happen through this interface.
 */
interface UserManagerInterface extends ManagerInterface
{
    /**
     * Find a user by its username.
     */
    public function findUserByUsername(string $username): ?UserInterface;

    /**
     * Finds a user by its email.
     */
    public function findUserByEmail(string $email): ?UserInterface;

    /**
     * Finds a user by its username or email.
     */
    public function findUserByUsernameOrEmail(string $usernameOrEmail): ?UserInterface;

    /**
     * Updates the canonical username and email fields for a user.
     */
    public function updateCanonicalFields(UserInterface $user): void;

    /**
     * Updates a user password if a plain password is set.
     */
    public function updatePassword(UserInterface $user): void;
}
