<?php

declare(strict_types=1);

namespace Talav\Component\Plan\Model;

use Doctrine\Common\Collections\Collection;

interface PlanInterface
{
    public function getFeatureValues(): Collection;

    public function addFeatureValue(FeatureValueInterface $featureValue): void;

    public function getFeatureValue(string $key): FeatureValueInterface;
}
