<?php

declare(strict_types=1);

namespace ResourceAppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Talav\Component\Resource\Model\ResourceTrait;

abstract class AbstractBook implements BookInterface
{
    use ResourceTrait;

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string|null
     */
    protected $title;

    /**
     * @var ArrayCollection
     */
    protected $authors;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function getAuthors(): ArrayCollection
    {
        return $this->authors;
    }
}
