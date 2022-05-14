<?php

declare(strict_types=1);

namespace Talav\StripeBundle\Message\Command;

use Stripe\Event;
use Talav\Component\Resource\Model\DomainEventInterface;

final class StripeEventCommand implements DomainEventInterface
{
    public function __construct(
        public Event $event
    ) {
    }
}
