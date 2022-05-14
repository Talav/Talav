<?php

declare(strict_types=1);

namespace Talav\StripeBundle\Enum;

enum TaxBehavior: string
{
    case INCLUSIVE = 'inclusive';
    case EXCLUSIVE = 'exclusive';
    case UNSPECIFIED = 'unspecified';
}
