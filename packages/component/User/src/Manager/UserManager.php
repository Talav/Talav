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
}
