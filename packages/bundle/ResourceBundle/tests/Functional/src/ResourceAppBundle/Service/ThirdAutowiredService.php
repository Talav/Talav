<?php

declare(strict_types=1);

namespace ResourceAppBundle\Service;

use ResourceAppBundle\Manager\AuthorManagerInterface;
use Talav\Component\Resource\Factory\FactoryInterface;
use Talav\Component\Resource\Manager\ManagerInterface;
use Talav\Component\Resource\Repository\RepositoryInterface;

final class ThirdAutowiredService
{
    /**
     * @var FactoryInterface
     */
    public $authorFactory;

    /**
     * @var RepositoryInterface
     */
    public $authorRepository;

    /**
     * @var AuthorManagerInterface
     */
    public $authorManager;

    public function __construct(
        FactoryInterface $authorFactory,
        RepositoryInterface $authorRepository,
        AuthorManagerInterface $authorManager
    ) {
        $this->authorFactory = $authorFactory;
        $this->authorRepository = $authorRepository;
        $this->authorManager = $authorManager;
    }

    public function getAuthorFactory(): FactoryInterface
    {
        return $this->authorFactory;
    }

    public function setAuthorFactory(FactoryInterface $authorFactory): void
    {
        $this->authorFactory = $authorFactory;
    }

    public function getAuthorRepository(): RepositoryInterface
    {
        return $this->authorRepository;
    }

    public function setAuthorRepository(RepositoryInterface $authorRepository): void
    {
        $this->authorRepository = $authorRepository;
    }

    public function getAuthorManager(): ManagerInterface
    {
        return $this->authorManager;
    }

    public function setAuthorManager(ManagerInterface $authorManager): void
    {
        $this->authorManager = $authorManager;
    }
}
