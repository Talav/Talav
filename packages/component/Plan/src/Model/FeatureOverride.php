<?php

declare(strict_types=1);

namespace Talav\Component\Plan\Model;

use Talav\Component\Resource\Model\ResourceTrait;

class FeatureOverride implements FeatureOverrideInterface
{
    use ResourceTrait;
    use FeatureValueTrait;

    /** @var SubscriberInterface */
    protected $user;

    public static function construct(FeatureInterface $feature, SubscriberInterface $user, $value)
    {
        $featureValue = new self();
        $featureValue->setFeature($feature);
        $featureValue->setUser($user);
        $featureValue->setValue($value);

        return $featureValue;
    }

    public function getUser(): SubscriberInterface
    {
        return $this->user;
    }

    public function setUser(SubscriberInterface $user): void
    {
        $this->user = $user;
    }
}
