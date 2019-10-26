<?php

declare(strict_types=1);

namespace Talav\Component\User\Model;

/**
 * Indicates models aware about user model.
 */
interface UserAwareInterface
{
    public function getUser(): ?UserInterface;

    public function setUser(?UserInterface $user): void;
}
