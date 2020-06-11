<?php

declare(strict_types=1);

namespace Talav\Component\User\Model;

trait CreatedBy
{
    /** @var UserInterface|null */
    protected $createdBy;

    /**
     * @return UserInterface|null
     */
    public function getCreatedBy(): ?UserInterface
    {
        return $this->createdBy;
    }

    /**
     * @param UserInterface|null $createdBy
     */
    public function setCreatedBy(?UserInterface $createdBy): void
    {
        $this->createdBy = $createdBy;
    }
}
