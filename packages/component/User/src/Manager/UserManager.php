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
use Talav\Component\User\Security\PasswordUpdaterInterface;
use Webmozart\Assert\Assert;

class UserManager extends ResourceManager implements UserManagerInterface
{
    private CanonicalizerInterface $canonicalizer;

    protected PasswordUpdaterInterface $passwordUpdater;

    public function __construct(
        string $className,
        EntityManagerInterface $em,
        FactoryInterface $factory,
        CanonicalizerInterface $canonicalizer,
        PasswordUpdaterInterface $passwordUpdater)
    {
        parent::__construct($className, $em, $factory);
        $this->canonicalizer = $canonicalizer;
        $this->passwordUpdater = $passwordUpdater;
    }

    public function getTypedRepository(): UserRepositoryInterface
    {
        $repository = $this->getRepository();
        Assert::isInstanceOf($repository, UserRepositoryInterface::class);

        return $repository;
    }

    /**
     * {@inheritdoc}
     */
    public function findUserByEmail(string $email): ?UserInterface
    {
        return $this->getTypedRepository()->findOneByEmail($this->canonicalizer->canonicalize($email));
    }

    /**
     * {@inheritdoc}
     */
    public function findUserByUsername(string $username): ?UserInterface
    {
        return $this->getTypedRepository()->findOneByUsername($this->canonicalizer->canonicalize($username));
    }

    /**
     * {@inheritdoc}
     */
    public function findUserByUsernameOrEmail(string $usernameOrEmail): ?UserInterface
    {
        if (filter_var($usernameOrEmail, \FILTER_VALIDATE_EMAIL)) {
            return $this->findUserByEmail($usernameOrEmail);
        }

        return $this->findUserByUsername($usernameOrEmail);
    }

    /**
     * {@inheritdoc}
     */
    public function updateCanonicalFields(UserInterface $user): void
    {
        // enforce non empty username
        if (empty($user->getUsername()) && !empty($user->getEmail())) {
            $user->setUsername($user->getEmail());
        }
        $user->setEmailCanonical($this->canonicalizer->canonicalize($user->getEmail()));
        $user->setUsernameCanonical($this->canonicalizer->canonicalize($user->getUsername()));
    }

    /**
     * {@inheritdoc}
     */
    public function updatePassword(UserInterface $user): void
    {
        $this->passwordUpdater->updatePassword($user);
    }

    /**
     * {@inheritdoc}
     */
    public function update(ResourceInterface $user, $flush = true): void
    {
        Assert::isInstanceOf($user, UserInterface::class);
        $this->updateCanonicalFields($user);
        $this->updatePassword($user);
        $this->add($user);

        if ($flush) {
            $this->em->flush();
        }
    }
}
