<?php

declare(strict_types=1);

namespace Talav\StripeBundle\Entity;

use DateTime;

trait CreatedTrait
{
    /**
     * Time at which the object was updated. Measured in seconds since the Unix epoch.
     */
    protected ?int $created = null;

    public function getCreated(): ?int
    {
        return $this->created;
    }

    public function setCreated(?int $created): void
    {
        $this->created = $created;
    }

    public function getCreatedDateTime(): ?DateTime
    {
        if (is_null($this->created)) {
            return null;
        }
        $date = new \DateTime();
        $date->setTimestamp($this->created);

        return $date;
    }

    public function setCreatedDateTime(?DateTime $created): void
    {
        $this->setCreated($created->getTimestamp());
    }
}
