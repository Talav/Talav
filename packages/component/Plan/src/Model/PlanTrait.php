<?php

declare(strict_types=1);

namespace Talav\Component\Plan\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

trait PlanTrait
{
    /** @var Collection */
    private $featureValues;

    /**
     * Returns a list of all features values for plan
     */
    public function getFeatureValues(): Collection
    {
        if (null === $this->featureValues) {
            $this->featureValues = new ArrayCollection();
        }

        return $this->featureValues;
    }

    /**
     * Adds new feature value to plan
     */
    public function addFeatureValue(FeatureValueInterface $featureValue): void
    {
        if ($this->getFeatureValues()->containsKey($featureValue->getFeature()->getKey())) {
            throw new \RuntimeException('Plan cannot have 2 features with the same key');
        }
        $this->getFeatureValues()->set($featureValue->getFeature()->getKey(), $featureValue);
        if ($featureValue->getPlan() != $this) {
            $featureValue->setPlan($this);
        }
    }

    public function getFeatureValue(string $key): FeatureValueInterface
    {
        return $this->getFeatureValues()->get($key);
    }
}
