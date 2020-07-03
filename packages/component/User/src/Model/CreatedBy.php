<?php

declare(strict_types=1);

namespace Talav\Component\User\Model;

trait CreatedBy
{
    /** @var UserInterface|null */
    protected $createdBy;

    public function getCreatedBy(): ?UserInterface
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?UserInterface $createdBy): void
    {
        $this->createdBy = $createdBy;
    }
}
