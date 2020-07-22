<?php

declare(strict_types=1);

namespace Talav\Component\User\Model;

trait UserAwareTrait
{
    protected ?UserInterface $user = null;

    public function getUser(): ?UserInterface
    {
        return $this->user;
    }

    public function setUser(?UserInterface $user): void
    {
        $this->user = $user;
    }
}
