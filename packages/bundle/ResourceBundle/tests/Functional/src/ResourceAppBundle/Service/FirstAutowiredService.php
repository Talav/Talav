<?php

declare(strict_types=1);

namespace ResourceAppBundle\Service;

use Talav\Component\Resource\Factory\FactoryInterface;
use Talav\Component\Resource\Manager\ManagerInterface;
use Talav\Component\Resource\Repository\RepositoryInterface;

final class FirstAutowiredService
{
    /**
     * @var FactoryInterface
     */
    public $bookFactory;

    /**
     * @var RepositoryInterface
     */
    public $bookRepository;

    /**
     * @var ManagerInterface
     */
    public $bookManager;

    public function __construct(
        FactoryInterface $bookFactory,
        RepositoryInterface $bookRepository,
        ManagerInterface $bookManager
    ) {
        $this->bookFactory = $bookFactory;
        $this->bookRepository = $bookRepository;
        $this->bookManager = $bookManager;
    }

    public function getBookFactory(): FactoryInterface
    {
        return $this->bookFactory;
    }

    public function getBookRepository(): RepositoryInterface
    {
        return $this->bookRepository;
    }

    public function getBookManager(): ManagerInterface
    {
        return $this->bookManager;
    }
}
