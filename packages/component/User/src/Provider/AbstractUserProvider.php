<?php

declare(strict_types=1);

namespace Talav\Component\User\Provider;

use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Talav\Component\User\Manager\UserManagerInterface;

abstract class AbstractUserProvider implements UserProviderInterface
{
    /** @var UserManagerInterface */
    protected $userManager;

    /**
     * AbstractUserProvider constructor.
     */
    public function __construct(UserManagerInterface $userManager)
    {
        $this->userManager = $userManager;
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByUsername($username): UserInterface
    {
        $user = $this->findUser($username);
        if (null === $user) {
            throw new UsernameNotFoundException(
                sprintf('Username "%s" does not exist.', $username)
            );
        }

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function refreshUser(UserInterface $user): UserInterface
    {
        if (!$this->supportsClass(get_class($user))) {
            throw new UnsupportedUserException(
                sprintf('Instances of "%s" are not supported.', get_class($user))
            );
        }
        $reloadedUser = $this->userManager->reload($user);
        if (null === $reloadedUser) {
            throw new UsernameNotFoundException(
                sprintf('User with ID "%d" could not be refreshed.', $user->getId())
            );
        }

        return $reloadedUser;
    }

    abstract protected function findUser(string $uniqueIdentifier): ?UserInterface;

    /**
     * {@inheritdoc}
     */
    public function supportsClass($class): bool
    {
        return $this->userManager->getClassName() === $class || is_subclass_of($class, $this->userManager->getClassName());
    }
}
