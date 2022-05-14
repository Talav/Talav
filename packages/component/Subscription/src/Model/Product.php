<?php

declare(strict_types=1);

namespace Talav\Component\Subscription\Model;

class Product
{
    protected iterable $features;

    public function addFeature(Feature $feature): void
    {
        $this->features[$feature->name] = $feature;
    }

    public function getFeature(string $key): Feature
    {
        if (!isset($this->features[$key])) {
            throw new \RuntimeException(sprintf('Feature %s is not found', $key));
        }

        return $this->features[$key];
    }
}
