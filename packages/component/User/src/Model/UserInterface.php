<?php

declare(strict_types=1);

namespace Talav\Component\User\Model;

use DateInterval;
use DateTimeInterface;
use Doctrine\Common\Collections\Collection;
use Serializable;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface as SymfonyUserInterface;
use Talav\Component\Resource\Model\ResourceInterface;

interface UserInterface extends SymfonyUserInterface, PasswordAuthenticatedUserInterface, ResourceInterface, Serializable
{
    /**
     * Sets the username.
     */
    public function setUsername(?string $username): void;

    /**
     * Gets the canonical username in search and sort queries.
     */
    public function getUsernameCanonical(): ?string;

    /**
     * Sets the canonical username.
     */
    public function setUsernameCanonical(?string $usernameCanonical): void;

    /**
     * Sets the email.
     */
    public function setEmail(?string $username): void;

    /**
     * Gets the email.
     */
    public function getEmail(): ?string;

    /**
     * Gets the canonical username in search and sort queries.
     */
    public function getEmailCanonical(): ?string;

    /**
     * Sets the canonical username.
     */
    public function setEmailCanonical(?string $usernameCanonical): void;

    public function setLocked(bool $locked): void;

    public function getEmailVerificationToken(): ?string;

    public function setEmailVerificationToken(?string $verificationToken): void;

    public function getPasswordResetToken(): ?string;

    public function setPasswordResetToken(?string $passwordResetToken): void;

    public function getPasswordRequestedAt(): ?DateTimeInterface;

    /**
     * Sets the timestamp that the user requested a password reset.
     */
    public function setPasswordRequestedAt(?DateTimeInterface $date): void;

    /**
     * Checks whether the password reset request has expired.
     *
     * @param DateInterval $ttl Requests older than this many seconds will be considered expired
     */
    public function isPasswordRequestNonExpired(DateInterval $ttl): bool;

    public function isVerified(): bool;

    public function getVerifiedAt(): ?DateTimeInterface;

    public function setVerifiedAt(?DateTimeInterface $verifiedAt): void;

    public function getExpiresAt(): ?DateTimeInterface;

    public function setExpiresAt(?DateTimeInterface $date): void;

    public function getCredentialsExpireAt(): ?DateTimeInterface;

    public function setCredentialsExpireAt(?DateTimeInterface $date): void;

    public function getLastLogin(): ?DateTimeInterface;

    public function setLastLogin(?DateTimeInterface $time): void;

    /**
     * Never use this to check if this user has access to anything!
     *
     * Use the AuthorizationChecker, or an implementation of AccessDecisionManager
     * instead, e.g.
     *
     *         $authorizationChecker->isGranted('ROLE_USER');
     */
    public function hasRole(string $role): bool;

    /**
     * Adds a role to the user.
     */
    public function addRole(string $role): void;

    /**
     * Removes a role to the user.
     */
    public function removeRole(string $role): void;

    /**
     * @return Collection|UserOAuthInterface[]
     */
    public function getOAuthAccounts(): Collection;

    public function getOAuthAccount(string $provider): ?UserOAuthInterface;

    public function addOAuthAccount(UserOAuthInterface $oauth): void;

    public function getFirstName(): ?string;

    public function setFirstName(?string $firstName): void;

    public function getLastName(): ?string;

    public function setLastName(?string $lastName): void;

    public function isEnabled(): bool;

    public function setEnabled(bool $enabled): void;

    /**
     * Sets the super admin status.
     */
    public function setSuperAdmin(bool $boolean): void;

    /**
     * Tells if the the given user has the super admin role.
     */
    public function isSuperAdmin(): bool;
}
