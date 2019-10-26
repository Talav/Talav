<?php

declare(strict_types=1);

namespace AppBundle\Service;

use AppBundle\Factory\AuthorFactoryInterface;
use AppBundle\Manager\AuthorManagerInterface;
use AppBundle\Repository\AuthorRepositoryInterface;

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

    /**
     * @return AuthorFactoryInterface
     */
    public function getAuthorFactory(): AuthorFactoryInterface
    {
        return $this->authorFactory;
    }

    /**
     * @return AuthorRepositoryInterface
     */
    public function getAuthorRepository(): AuthorRepositoryInterface
    {
        return $this->authorRepository;
    }

    /**
     * @return AuthorManagerInterface
     */
    public function getAuthorManager(): AuthorManagerInterface
    {
        return $this->authorManager;
    }
}