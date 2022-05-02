<?php

declare(strict_types=1);

namespace Talav\Component\User\Message\Command;

use Talav\Component\Resource\Model\DomainEventInterface;
use Talav\Component\User\Model\UserInterface;

final class UpdatePasswordCommand implements DomainEventInterface
{
    public function __construct(
        public UserInterface $user,
        public string $password
    ) {
    }
}
