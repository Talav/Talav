<?php

declare(strict_types=1);

namespace Talav\StripeBundle\Enum;

enum PriceTiersMode: string
{
    // In graduated tiering, pricing can change as the quantity grows.
    case SUM = 'graduated';

    // In volume-based tiering, the maximum quantity within a period determines the per unit price.
    case LAST_DURING_PERIOD = 'volume';
}
