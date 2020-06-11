<?php

declare(strict_types=1);

namespace Talav\Component\Plan\Model;

use Doctrine\Common\Collections\Collection;
use Talav\Component\Plan\Comparator\FeatureValueMap;

interface SubscriberInterface
{
    /**
     * @return Collection&FeatureOverride[]
     */
    public function getFeatureOverrides(): Collection;

    public function addFeatureOverride(FeatureOverrideInterface $featureValueOverride): void;

    public function getPlan(): ?PlanInterface;

    public function setPlan(PlanInterface $plan): void;

    public function getFeatureValueMap(): FeatureValueMap;
}
