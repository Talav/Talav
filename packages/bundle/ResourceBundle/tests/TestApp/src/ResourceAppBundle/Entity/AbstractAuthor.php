<?php

declare(strict_types=1);

namespace ResourceAppBundle\Entity;

use Talav\Component\Resource\Model\ResourceTrait;
use Talav\Component\Resource\Model\Timestampable;

abstract class AbstractAuthor implements AuthorInterface
{
    use ResourceTrait;
    use Timestampable;

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string|null
     */
    protected $name;

    /**
     * @var BookInterface
     */
    protected $book;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getBook(): BookInterface
    {
        return $this->book;
    }

    public function setBook(BookInterface $book): void
    {
        $this->book = $book;
    }
}
