<?php

declare(strict_types=1);

namespace Talav\Component\User\Model;

trait UpdatedBy
{
    /** @var UserInterface|null */
    protected $updatedBy;

    public function getUpdatedBy(): ?UserInterface
    {
        return $this->updatedBy;
    }

    public function setUpdatedBy(?UserInterface $updatedBy): void
    {
        $this->updatedBy = $updatedBy;
    }
}
