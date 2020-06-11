<?php

declare(strict_types=1);

namespace Talav\Component\Plan\Model;

use Talav\Component\Resource\Model\ResourceTrait;

class FeatureValue implements FeatureValueInterface
{
    use ResourceTrait;
    use FeatureValueTrait;

    /** @var PlanInterface */
    protected $plan;

    public static function construct(FeatureInterface $feature, PlanInterface $plan, $value)
    {
        $featureValue = new self();
        $featureValue->setFeature($feature);
        $featureValue->setPlan($plan);
        $featureValue->setValue($value);

        return $featureValue;
    }

    public function getPlan(): PlanInterface
    {
        return $this->plan;
    }

    public function setPlan(PlanInterface $plan): void
    {
        $this->plan = $plan;
        $plan->addFeatureValue($this);
    }
}
