<?php

declare(strict_types=1);

namespace Talav\StripeBundle\Entity;

trait MetadataTrait
{
    /**
     * Set of key-value pairs that you can attach to an object.
     * This can be useful for storing additional information about the object in a structured format.
     */
    protected array $metadata = [];

    public function getMetadata(): array
    {
        return $this->metadata;
    }

    public function setMetadata(array $metadata): void
    {
        $this->metadata = $metadata;
    }
}
