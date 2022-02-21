<?php

declare(strict_types=1);

namespace Talav\Component\User\Tests\Helper;

use Talav\Component\User\Model\CredentialsHolderInterface;

final class User implements CredentialsHolderInterface
{
    private ?string $plainPassword = null;

    private ?string $password = null;

    private ?string $salt = null;

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(?string $encodedPassword): void
    {
        $this->password = $encodedPassword;
    }

    public function getSalt(): ?string
    {
        return $this->salt;
    }

    public function eraseCredentials(): void
    {
        $this->plainPassword = null;
    }
}
