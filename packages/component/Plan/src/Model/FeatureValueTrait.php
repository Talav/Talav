<?php

declare(strict_types=1);

namespace Talav\Component\Plan\Model;

trait FeatureValueTrait
{
    /** @var FeatureInterface */
    protected $feature;

    /**
     * Feature value
     *
     * @var string
     */
    protected $value;

    public function getValue()
    {
        switch ($this->getFeature()->getType()) {
            case FeatureInterface::TYPE_BOOL: return (bool) $this->value;
            case FeatureInterface::TYPE_INT: return (int) $this->value;
            default: throw new \RuntimeException('Unknown type: ' . $this->getFeature()->getType());
        }
    }

    public function setValue($value): void
    {
        $this->value = (string) $value;
    }

    public function getFeature(): FeatureInterface
    {
        return $this->feature;
    }

    public function setFeature(FeatureInterface $feature): void
    {
        $this->feature = $feature;
    }
}
