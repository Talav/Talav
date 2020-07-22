<?php

declare(strict_types=1);

namespace Talav\Component\Resource\Manager;

use Talav\Component\Resource\Factory\FactoryInterface;
use Talav\Component\Resource\Model\ResourceInterface;
use Talav\Component\Resource\Repository\RepositoryInterface;

interface ManagerInterface
{
    public function getClassName(): string;

    public function getRepository(): RepositoryInterface;

    public function getFactory(): FactoryInterface;

    public function create(): ResourceInterface;

    public function add(ResourceInterface $resource): void;

    public function remove(ResourceInterface $resource): void;

    public function update(ResourceInterface $resource, bool $flush = false): void;

    public function reload(ResourceInterface $resource): ResourceInterface;

    public function flush(): void;
}
