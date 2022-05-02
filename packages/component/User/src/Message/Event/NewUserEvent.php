<?php

declare(strict_types=1);

namespace Talav\Component\User\Message\Event;

use Talav\Component\Resource\Model\DomainEventInterface;

final class NewUserEvent implements DomainEventInterface
{
    public function __construct(public mixed $id)
    {
    }
}
