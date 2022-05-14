<?php

declare(strict_types=1);

namespace Talav\StripeBundle\Enum;

enum RecurringAggregateUsage: string
{
    // summing up all usage during a period
    case SUM = 'sum';

    // using the last usage record reported within a period
    case LAST_DURING_PERIOD = 'last_during_period';

    // using the last usage record ever (across period bounds)
    case LAST_EVER = 'last_ever';

    // uses the usage record with the maximum reported usage during a period
    case MAX = 'max';
}
