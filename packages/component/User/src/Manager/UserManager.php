<?php

declare(strict_types=1);

namespace Talav\Component\User\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Talav\Component\Resource\Factory\FactoryInterface;
use Talav\Component\Resource\Manager\ResourceManager;
use Talav\Component\Resource\Model\ResourceInterface;
use Talav\Component\User\Canonicalizer\CanonicalizerInterface;
use Talav\Component\User\Model\UserInterface;
use Talav\Component\User\Repository\UserRepositoryInterface;
use Webmozart\Assert\Assert;

class UserManager extends ResourceManager implements UserManagerInterface
{
    public function __construct(
        protected string $className,
        protected EntityManagerInterface $em,
        protected FactoryInterface $factory,
        protected CanonicalizerInterface $canonicalizer
    ) {
        parent::__construct($className, $em, $factory);
    }

    public function getTypedRepository(): UserRepositoryInterface
    {
        $repository = $this->getRepository();
        Assert::isInstanceOf($repository, UserRepositoryInterface::class);

        return $repository;
    }

    public function findUserByEmail(string $email): ?UserInterface
    {
        return $this->getTypedRepository()->findOneByEmail($this->canonicalizer->canonicalize($email));
    }

    public function findUserByUsername(string $username): ?UserInterface
    {
        return $this->getTypedRepository()->findOneByUsername($this->canonicalizer->canonicalize($username));
    }

    public function findUserByUsernameOrEmail(string $usernameOrEmail): ?UserInterface
    {
        if (filter_var($usernameOrEmail, \FILTER_VALIDATE_EMAIL)) {
            return $this->findUserByEmail($usernameOrEmail);
        }

        return $this->findUserByUsername($usernameOrEmail);
    }

    public function updateCanonicalFields(UserInterface $user): void
    {
        // enforce non empty username
        if (empty($user->getUsername()) && !empty($user->getEmail())) {
            $user->setUsername($user->getEmail());
        }
        $user->setEmailCanonical($this->canonicalizer->canonicalize($user->getEmail()));
        $user->setUsernameCanonical($this->canonicalizer->canonicalize($user->getUsername()));
    }

    public function update(ResourceInterface $user, $flush = true): void
    {
        Assert::isInstanceOf($user, UserInterface::class);
        $this->updateCanonicalFields($user);
        $this->add($user);

        if ($flush) {
            $this->em->flush();
        }
    }

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
        $user = $this->findUserByUsername($username);
        if (!$user) {
            throw new InvalidArgumentException(sprintf('User identified by "%s" username does not exist.', $username));
        }

        return $user;
    }
}
