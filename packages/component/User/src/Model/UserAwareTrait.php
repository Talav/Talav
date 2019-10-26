<?php

declare(strict_types=1);

namespace Talav\Component\User\Model;

trait UserAwareTrait
{
    /** @var UserInterface|null */
    protected $user;

    public function getUser(): ?UserInterface
    {
        return $this->user;
    }

    public function setUser(?UserInterface $user): void
    {
        $this->user = $user;
    }
}
