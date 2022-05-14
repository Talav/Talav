<?php

declare(strict_types=1);

namespace Talav\StripeBundle\Message\Event;

use Talav\Component\Resource\Model\DomainEventInterface;

final class NewProductEvent implements DomainEventInterface
{
    public function __construct(public mixed $id)
    {
    }
}
