<?php

declare(strict_types=1);

namespace Talav\Component\Subscription\Comparator;

use Talav\Component\User\Model\UserInterface;

interface ValueProviderInterface
{
    public function getFeatureValue(UserInterface $user, string $feature): iterable;

    public function registerProvider(string $key, \Closure $closure): void;
}
