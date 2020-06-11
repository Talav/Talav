<?php

declare(strict_types=1);

namespace Talav\Component\Plan\Tests\Helper;

use Talav\Component\Plan\Model\SubscriberInterface;
use Talav\Component\Plan\Model\SubscriberTrait;

class User implements SubscriberInterface
{
    use SubscriberTrait;
}
