<?php

declare(strict_types=1);

namespace ResourceAppBundle\Entity;

use Talav\Component\Resource\Model\ResourceTrait;

abstract class AbstractAuthor implements AuthorInterface
{
    use ResourceTrait;

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

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return BookInterface
     */
    public function getBook(): BookInterface
    {
        return $this->book;
    }

    /**
     * @param BookInterface $book
     */
    public function setBook(BookInterface $book): void
    {
        $this->book = $book;
    }
}