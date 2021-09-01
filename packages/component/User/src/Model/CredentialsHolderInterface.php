<?php

declare(strict_types=1);

namespace Talav\Component\User\Model;

interface CredentialsHolderInterface
{
    /**
     * Gets plain password.
     */
    public function getPlainPassword(): ?string;

    /**
     * Sets plain password.
     */
    public function setPlainPassword(?string $plainPassword): void;

    /**
     * Sets encoded password.
     */
    public function setPassword(?string $encodedPassword): void;

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials();

    /**
     * Returns the password used to authenticate the user.
     *
     * This should be the hashed password. On authentication, a plain-text
     * password will be hashed, and then compared to this value.
     *
     * @return string|null The hashed password if any
     */
    public function getPassword();
}
