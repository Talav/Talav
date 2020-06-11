<?php

declare(strict_types=1);

namespace Talav\Component\Plan\Model;

use Talav\Component\Resource\Model\ResourceTrait;

class Feature implements FeatureInterface
{
    use ResourceTrait;

    /**
     * Feature name
     *
     * @var string
     */
    protected $name;

    /**
     * Feature string key
     *
     * @var string
     */
    protected $key;

    /**
     * Return type for value
     *
     * @var string
     */
    protected $type;

    public static function construct($name, $key, $type)
    {
        $feature = new self();
        $feature->setName($name);
        $feature->setType($type);
        $feature->setKey($key);

        return $feature;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function setKey(string $key): void
    {
        $this->key = $key;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        if (!$this->isValidType($type)) {
            throw new \RuntimeException('Invalid type');
        }
        $this->type = $type;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function isInt(): bool
    {
        return FeatureInterface::TYPE_INT == $this->getType();
    }

    public function isBool(): bool
    {
        return FeatureInterface::TYPE_BOOL == $this->getType();
    }

    /**
     * Checks if type is valid
     */
    protected function isValidType(string $type): bool
    {
        return in_array($type, FeatureInterface::TYPES);
    }
}
