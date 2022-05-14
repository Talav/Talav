<?php

declare(strict_types=1);

namespace Talav\StripeBundle\Enum;

enum RecurringUsageType: string
{
    case METERED = 'metered';
    case LICENSED = 'licensed';
}
