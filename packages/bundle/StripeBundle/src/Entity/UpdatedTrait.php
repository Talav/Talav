<?php

declare(strict_types=1);

namespace Talav\StripeBundle\Entity;

use DateTime;

trait UpdatedTrait
{
    /**
     * Time at which the object was updated. Measured in seconds since the Unix epoch.
     */
    protected ?int $updated = null;

    public function getUpdated(): ?int
    {
        return $this->updated;
    }

    public function setUpdated(?int $updated)
    {
        $this->updated = $updated;
    }

    public function getUpdatedDateTime(): ?DateTime
    {
        if (is_null($this->updated)) {
            return null;
        }
        $date = new \DateTime();
        $date->setTimestamp($this->updated);

        return $date;
    }

    public function setUpdatedDateTime(?DateTime $updated)
    {
        $this->setUpdated($updated->getTimestamp());
    }
}
