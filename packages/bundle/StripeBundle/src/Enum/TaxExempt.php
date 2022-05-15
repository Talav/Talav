<?php

declare(strict_types=1);

namespace Talav\StripeBundle\Enum;

enum TaxExempt: string
{
    case NONE = 'none';
    case EXEMPT = 'exempt';
    case REVERSE = 'reverse';
}
