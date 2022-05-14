<?php

declare(strict_types=1);

namespace Talav\StripeBundle\Entity;

trait ActiveTrait
{
    /**
     * Whether the object is currently available.
     */
    protected bool $active = false;

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): void
    {
        $this->active = $active;
    }
}
