<?php

declare(strict_types=1);

namespace Talav\StripeBundle\Entity;

trait NameTrait
{
    /**
     * An arbitrary string attached to the object. Neant to be displayable to the customer.
     */
    protected ?string $name = null;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }
}
