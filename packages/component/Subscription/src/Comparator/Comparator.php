<?php

declare(strict_types=1);

namespace Talav\Component\Plan\Comparator;

use Symfony\Component\Security\Core\Security;
use Talav\Component\Subscription\Comparator\ValueProviderInterface;
use Talav\Component\Subscription\Model\SubscriberInterface;

final class Comparator
{
    public function __construct(
        private Security $security,
        private ValueProviderInterface $provider
    ) {
    }

    public function isAllowed(string $key): bool
    {
        $user = $this->security->getUser();
        if (!($user instanceof SubscriberInterface)) {
            throw new \RuntimeException('User must implement SubscriberInterface');
        }
        $subscription = $user->getActiveSubscription();

        $feature = $user->getActiveSubscription()->getProduct()->getFeature($key);
        if (!$feature->isEnabled) {
            return false;
        }
        if ($feature->isUnlimited) {
            return true;
        }

        return $feature->value <= $this->provider->getFeatureValue($user, $key);
    }
}
