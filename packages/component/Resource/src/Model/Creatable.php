<?php

declare(strict_types=1);

namespace Talav\Component\Resource\Model;

use DateTime;

trait Creatable
{
    protected ?DateTime $createdAt;

    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}
