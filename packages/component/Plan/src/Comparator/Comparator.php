<?php

declare(strict_types=1);

namespace Talav\Component\Plan\Comparator;

use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\Security;
use Talav\Component\Plan\Model\SubscriberInterface;

final class Comparator
{
    /**
     * Current values for features for current user
     *
     * @var Collection
     */
    private $currentValues;

    /** @var ValueProviderInterface */
    private $provider;

    /** @var Security */
    private $security;

    public function __construct(Security $security, ValueProviderInterface $provider)
    {
        $this->security = $security;
        $this->provider = $provider;
    }

    public function isAllowed($key)
    {
        $user = $this->security->getUser();
        if (!($user instanceof SubscriberInterface)) {
            throw new \RuntimeException('User must implement SubscriberInterface');
        }
        $featureMap = $user->getFeatureValueMap();
        if (!$featureMap->containsKey($key)) {
            throw new \RuntimeException("Unknown plan feature: $key");
        }
        $feature = $user->getPlan()->getFeatureValue($key)->getFeature();
        if ($feature->isBool()) {
            return (bool) $featureMap->get($key);
        }
        if ($feature->isInt()) {
            if (!$this->getCurrentValues()->containsKey($key)) {
                throw new \RuntimeException("Unknown current value for feature: $key");
            }

            return (int) $featureMap->get($key) > $this->getCurrentValues()->get($key);
        }

        return false;
    }

    /**
     * Gets current values for feature list
     */
    private function getCurrentValues(): Collection
    {
        if (null === $this->currentValues) {
            $this->currentValues = $this->provider->getCurrentValuesList();
        }

        return $this->currentValues;
    }
}
