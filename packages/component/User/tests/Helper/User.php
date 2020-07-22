<?php

declare(strict_types=1);

namespace Talav\Component\User\Tests\Helper;

use Talav\Component\User\Model\CredentialsHolderInterface;

final class User implements CredentialsHolderInterface
{
    private ?string $plainPassword = null;

    private ?string $password = null;

    private ?string $salt = null;

    /**
     * {@inheritdoc}
     */
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    /**
     * {@inheritdoc}
     */
    public function setPlainPassword(?string $plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }

    /**
     * {@inheritdoc}
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * {@inheritdoc}
     */
    public function setPassword(?string $encodedPassword): void
    {
        $this->password = $encodedPassword;
    }

    /**
     * {@inheritdoc}
     */
    public function getSalt(): ?string
    {
        return $this->salt;
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials(): void
    {
        $this->plainPassword = null;
    }
}
