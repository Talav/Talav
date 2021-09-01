<?php

declare(strict_types=1);

namespace ResourceAppBundle\Service;

use ResourceAppBundle\Factory\AuthorFactoryInterface;
use ResourceAppBundle\Manager\AuthorManagerInterface;
use ResourceAppBundle\Repository\AuthorRepositoryInterface;

final class SecondAutowiredService
{
    /**
     * @var AuthorFactoryInterface
     */
    public $authorFactory;

    /**
     * @var AuthorRepositoryInterface
     */
    public $authorRepository;

    /**
     * @var AuthorManagerInterface
     */
    public $authorManager;

    public function __construct(
        AuthorFactoryInterface $authorFactory,
        AuthorRepositoryInterface $authorRepository,
        AuthorManagerInterface $authorManager
    ) {
        $this->authorFactory = $authorFactory;
        $this->authorRepository = $authorRepository;
        $this->authorManager = $authorManager;
    }

    public function getAuthorFactory(): AuthorFactoryInterface
    {
        return $this->authorFactory;
    }

    public function getAuthorRepository(): AuthorRepositoryInterface
    {
        return $this->authorRepository;
    }

    public function getAuthorManager(): AuthorManagerInterface
    {
        return $this->authorManager;
    }
}
