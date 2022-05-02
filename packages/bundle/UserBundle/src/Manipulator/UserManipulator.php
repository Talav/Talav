<?php

declare(strict_types=1);

namespace Talav\UserBundle\Manipulator;

use InvalidArgumentException;
use Talav\Component\User\Manager\UserManagerInterface;
use Talav\Component\User\Model\UserInterface;

class UserManipulator
{
    private UserManagerInterface $userManager;

    public function __construct(UserManagerInterface $userManager)
    {
        $this->userManager = $userManager;
    }

    /**
     * Activates the given user.
     */
    public function activate(string $username): void
    {
        $user = $this->findUserByUsernameOrThrowException($username);
        $user->setEnabled(true);
        $this->userManager->update($user, true);
    }

    /**
     * Deactivates the given user.
     */
    public function deactivate(string $username): void
    {
        $user = $this->findUserByUsernameOrThrowException($username);
        $user->setEnabled(false);
        $this->userManager->update($user, true);
    }

    /**
     * Changes the password for the given user.
     */
    public function changePassword(string $username, string $password): void
    {
        $user = $this->findUserByUsernameOrThrowException($username);
        $user->setPlainPassword($password);
        $this->userManager->update($user, true);
    }

    /**
     * Promotes the given user.
     */
    public function promote(string $username): void
    {
        $user = $this->findUserByUsernameOrThrowException($username);
        $user->setSuperAdmin(true);
        $this->userManager->update($user, true);
    }

    /**
     * Demotes the given user.
     */
    public function demote(string $username): void
    {
        $user = $this->findUserByUsernameOrThrowException($username);
        $user->setSuperAdmin(false);
        $this->userManager->update($user, true);
    }

    /**
     * Adds role to the given user.
     *
     * @return bool true if role was added, false if user already had the role
     */
    public function addRole(string $username, string $role): bool
    {
        $user = $this->findUserByUsernameOrThrowException($username);
        if ($user->hasRole($role)) {
            return false;
        }
        $user->addRole($role);
        $this->userManager->update($user, true);

        return true;
    }

    /**
     * Removes role from the given user.
     *
     * @return bool true if role was removed, false if user didn't have the role
     */
    public function removeRole(string $username, string $role): bool
    {
        $user = $this->findUserByUsernameOrThrowException($username);
        if (!$user->hasRole($role)) {
            return false;
        }
        $user->removeRole($role);
        $this->userManager->update($user, true);

        return true;
    }

    /**
     * Finds a user by his username and throws an exception if we can't find it.
     *
     * @throws InvalidArgumentException When user does not exist
     */
    private function findUserByUsernameOrThrowException(string $username): UserInterface
    {
        $user = $this->userManager->findUserByUsername($username);
        if (!$user) {
            throw new InvalidArgumentException(sprintf('User identified by "%s" username does not exist.', $username));
        }

        return $user;
    }
}
