<?php

declare(strict_types=1);

namespace Talav\Component\Resource\Metadata;

use InvalidArgumentException;

interface MetadataInterface
{
    public function getAlias(): string;

    public function getApplicationName(): string;

    public function getName(): string;

    public function getHumanizedName(): string;

    public function getPluralName(): string;

    /**
     * @throws InvalidArgumentException
     */
    public function getParameter(string $name);

    /**
     * Return all the metadata parameters.
     */
    public function getParameters(): array;

    public function hasParameter(string $name): bool;

    /**
     * @throws InvalidArgumentException
     */
    public function getClass(string $name): string;

    public function hasClass(string $name): bool;

    public function getServiceId(string $serviceName): string;

    public function getServiceClass(string $serviceName): string;
}
