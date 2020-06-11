<?php

declare(strict_types=1);

namespace Talav\Component\Plan\Model;

use Talav\Component\Resource\Model\ResourceInterface;

interface FeatureInterface extends ResourceInterface
{
    public const TYPE_INT = 'integer';

    public const TYPE_BOOL = 'boolean';

    public const TYPES = [
        self::TYPE_INT,
        self::TYPE_BOOL,
    ];

    public function getKey(): string;

    public function setKey(string $key): void;

    public function getType(): string;

    public function setType(string $type): void;

    public function getName(): string;

    public function setName(string $name): void;

    public function isInt(): bool;

    public function isBool(): bool;
}
