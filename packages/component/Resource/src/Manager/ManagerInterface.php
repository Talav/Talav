<?php

declare(strict_types=1);

namespace Talav\Component\Resource\Manager;

use Talav\Component\Resource\Factory\FactoryInterface;
use Talav\Component\Resource\Model\ResourceInterface;
use Talav\Component\Resource\Repository\RepositoryInterface;

interface ManagerInterface
{
    /**
     * @return string
     */
    public function getClassName(): string;

    /**
     * @return RepositoryInterface
     */
    public function getRepository();

    /**
     * @return FactoryInterface
     */
    public function getFactory();

    /**
     * @return ResourceInterface
     */
    public function create(): ResourceInterface;

    /**
     * @param ResourceInterface $resource
     */
    public function add(ResourceInterface $resource): void;

    /**
     * @param ResourceInterface $resource
     */
    public function remove(ResourceInterface $resource): void;

    /**
     * @param ResourceInterface $resource
     */
    public function update(ResourceInterface $resource, $flush = false): void;

    /**
     * @param ResourceInterface $resource
     * @return ResourceInterface
     */
    public function reload(ResourceInterface $resource): ResourceInterface;
}
