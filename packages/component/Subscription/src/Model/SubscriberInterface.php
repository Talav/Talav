<?php

declare(strict_types=1);

namespace Talav\Component\Subscription\Model;

interface SubscriberInterface
{
    public function getActiveSubscription(): ?Subscription;
}
