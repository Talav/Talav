<?php

declare(strict_types=1);

namespace Talav\StripeBundle\Enum;

enum BillingSchema: string
{
    case PER_UNIT = 'per_unit';
    case TIERED = 'tiered';
}
