<?php

declare(strict_types=1);

namespace Talav\Component\Plan\Comparator;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Talav\Component\Plan\Model\PlanInterface;

final class FeatureValueMap
{
    public $features;

    /** @var Collection */
    private $featureValues;

    public function __construct($featureValues)
    {
        $this->featureValues = new ArrayCollection();
        foreach ($featureValues as $featureValue) {
            $this->featureValues->set($featureValue->getFeature()->getKey(), $featureValue->getValue());
        }
    }

    /**
     * Creates new FeatureList from plan
     *
     * @return FeatureValueMap
     */
    public static function fromPlan(PlanInterface $plan): self
    {
        return new self($plan->getFeatureValues());
    }

    /**
     * Creates new FeatureList from plan and a list of specific overrides
     */
    public static function fromPlanWithOverrides(PlanInterface $plan, Collection $overrides): self
    {
        $indexedOver = new ArrayCollection();
        foreach ($overrides as $override) {
            $indexedOver->set($override->getFeature()->getKey(), $override);
        }
        $featureValues = [];
        foreach ($plan->getFeatureValues() as $featureValue) {
            if ($indexedOver->containsKey($featureValue->getFeature()->getKey())) {
                $featureValues[] = $indexedOver->get($featureValue->getFeature()->getKey());
            } else {
                $featureValues[] = $featureValue;
            }
        }

        return new self($featureValues);
    }

    public function containsKey($key): bool
    {
        return $this->featureValues->containsKey($key);
    }

    public function get($key)
    {
        return $this->featureValues->get($key);
    }
}
