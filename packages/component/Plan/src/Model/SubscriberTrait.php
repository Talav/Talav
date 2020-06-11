<?php

declare(strict_types=1);

namespace Talav\Component\Plan\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Talav\Component\Plan\Comparator\FeatureValueMap;

/**
 * Simplifies work with feature
 */
trait SubscriberTrait
{
    /** @var Collection&FeatureOverride[] */
    protected $featureOverrides;

    /** @var PlanInterface */
    protected $plan;

    /**
     * Returns a list of feature value overrides
     *
     * @return Collection&FeatureOverride[]
     */
    public function getFeatureOverrides(): Collection
    {
        if (null === $this->featureOverrides) {
            $this->featureOverrides = new ArrayCollection();
        }

        return $this->featureOverrides;
    }

    /**
     * Adds new feature override
     */
    public function addFeatureOverride(FeatureOverrideInterface $featureOverride): void
    {
        if ($this->getFeatureOverrides()->containsKey($featureOverride->getFeature()->getKey())) {
            throw new \RuntimeException('Plan cannot have 2 features with the same key');
        }
        $this->getFeatureOverrides()->set($featureOverride->getFeature()->getKey(), $featureOverride);
        if ($featureOverride->getUser() != $this) {
            $featureOverride->setUser($this);
        }
    }

    /**
     * @return PlanInterface
     */
    public function getPlan(): ?PlanInterface
    {
        return $this->plan;
    }

    public function setPlan(PlanInterface $plan): void
    {
        $this->plan = $plan;
    }

    public function getFeatureValueMap(): FeatureValueMap
    {
        return FeatureValueMap::fromPlanWithOverrides($this->getPlan(), $this->getFeatureOverrides());
    }
}
