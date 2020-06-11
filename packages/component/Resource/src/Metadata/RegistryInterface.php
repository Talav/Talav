<?php

declare(strict_types=1);

namespace Talav\Component\Resource\Metadata;

use InvalidArgumentException;

/**
 * Interface for the registry of all resources.
 */
interface RegistryInterface
{
    /**
     * @return iterable|MetadataInterface[]
     */
    public function getAll(): iterable;

    /**
     * @throws InvalidArgumentException
     */
    public function get(string $alias): MetadataInterface;

    /**
     * @throws InvalidArgumentException
     */
    public function getByClass(string $className): MetadataInterface;

    public function add(MetadataInterface $metadata): void;

    public function addFromAliasAndConfiguration(string $alias, array $configuration): void;
}
