<?php

declare(strict_types=1);

namespace Talav\StripeBundle\Entity;

trait DescriptionTrait
{
    /**
     * An arbitrary string attached to the object. Often useful for displaying to users.
     */
    protected ?string $description = null;

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }
}
