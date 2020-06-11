<?php

declare(strict_types=1);

namespace Talav\Component\Plan\Model;

use Talav\Component\Resource\Model\ResourceInterface;

interface FeatureValueInterface extends ResourceInterface
{
    public function getValue();

    /**
     * @param $value
     */
    public function setValue($value): void;

    public function getFeature(): FeatureInterface;

    public function setFeature(FeatureInterface $feature): void;

    public function getPlan(): PlanInterface;

    public function setPlan(PlanInterface $plan): void;
}
