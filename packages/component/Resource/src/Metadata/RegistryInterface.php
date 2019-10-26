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
     * @param string $alias
     * @return MetadataInterface
     * @throws InvalidArgumentException
     */
    public function get(string $alias): MetadataInterface;

    /**
     * @param string $className
     * @return MetadataInterface
     * @throws InvalidArgumentException
     */
    public function getByClass(string $className): MetadataInterface;

    /**
     * @param MetadataInterface $metadata
     */
    public function add(MetadataInterface $metadata): void;

    /**
     * @param string $alias
     * @param array $configuration
     */
    public function addFromAliasAndConfiguration(string $alias, array $configuration): void;
}