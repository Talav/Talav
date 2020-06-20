<?php

declare(strict_types=1);

namespace Talav\Component\Resource\Model;

use DateTime;

trait Creatable
{
    /**
     * @var ?DateTime
     */
    protected $createdAt;

    /**
     * @return DateTime|null
     */
    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param DateTime|null $createdAt
     */
    public function setCreatedAt(?DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}
