<?php

declare(strict_types=1);

namespace Talav\StripeBundle\Enum;

enum RecurringInterval: string
{
    case MONTH = 'month';
    case YEAR = 'year';
    case WEEK = 'week';
    case DAY = 'day';
}
