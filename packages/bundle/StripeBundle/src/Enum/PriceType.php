<?php

declare(strict_types=1);

namespace Talav\StripeBundle\Enum;

enum PriceType: string
{
    case ONE_TIME = 'one_time';
    case RECURRING = 'recurring';
}
