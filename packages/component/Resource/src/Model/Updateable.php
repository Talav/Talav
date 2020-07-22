<?php

declare(strict_types=1);

namespace Talav\Component\Resource\Model;

use DateTime;

trait Updateable
{
    protected ?DateTime $updatedAt;

    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}
