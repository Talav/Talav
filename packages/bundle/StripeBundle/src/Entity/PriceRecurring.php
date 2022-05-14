<?php

declare(strict_types=1);

namespace Talav\StripeBundle\Entity;

use Talav\StripeBundle\Enum\RecurringAggregateUsage;
use Talav\StripeBundle\Enum\RecurringInterval;
use Talav\StripeBundle\Enum\RecurringUsageType;

class PriceRecurring
{
    /**
     * Specifies a usage aggregation strategy for prices of usage_type=metered.
     */
    protected ?RecurringAggregateUsage $aggregateUsage = null;

    /**
     * The frequency at which a subscription is billed. One of day, week, month or year.
     */
    protected ?RecurringInterval $interval = null;

    /**
     * The number of intervals (specified in the interval attribute) between subscription billings. For example, interval=month and interval_count=3 bills every 3 months.
     */
    protected ?int $intervalCount = null;

    /**
     * The frequency at which a subscription is billed. One of day, week, month or year.
     */
    protected ?RecurringUsageType $usageType = null;

    public function getInterval(): ?RecurringInterval
    {
        return $this->interval;
    }

    public function setInterval(?RecurringInterval $interval): void
    {
        $this->interval = $interval;
    }

    public function getIntervalCount(): ?int
    {
        return $this->intervalCount;
    }

    public function setIntervalCount(?int $intervalCount): void
    {
        $this->intervalCount = $intervalCount;
    }

    public function getAggregateUsage(): ?RecurringAggregateUsage
    {
        return $this->aggregateUsage;
    }

    public function setAggregateUsage(?RecurringAggregateUsage $aggregateUsage): void
    {
        $this->aggregateUsage = $aggregateUsage;
    }

    public function getUsageType(): ?RecurringUsageType
    {
        return $this->usageType;
    }

    public function setUsageType(?RecurringUsageType $usageType): void
    {
        $this->usageType = $usageType;
    }
}
