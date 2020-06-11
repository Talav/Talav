<?php

declare(strict_types=1);

namespace Talav\Component\Resource\Manager;

use Talav\Component\Resource\Factory\FactoryInterface;
use Talav\Component\Resource\Model\ResourceInterface;
use Talav\Component\Resource\Repository\RepositoryInterface;

interface ManagerInterface
{
    public function getClassName(): string;

    /**
     * @return RepositoryInterface
     */
    public function getRepository();

    /**
     * @return FactoryInterface
     */
    public function getFactory();

    public function create(): ResourceInterface;

    public function add(ResourceInterface $resource): void;

    public function remove(ResourceInterface $resource): void;

    public function update(ResourceInterface $resource, $flush = false): void;

    public function reload(ResourceInterface $resource): ResourceInterface;

    public function flush(): void;
}
