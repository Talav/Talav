<?php

declare(strict_types=1);

namespace Talav\Component\User\Model;

trait UpdatedBy
{
    /** @var UserInterface|null */
    protected $updatedBy;

    /**
     * @return UserInterface|null
     */
    public function getUpdatedBy(): ?UserInterface
    {
        return $this->updatedBy;
    }

    /**
     * @param UserInterface|null $updatedBy
     */
    public function setUpdatedBy(?UserInterface $updatedBy): void
    {
        $this->updatedBy = $updatedBy;
    }
}
