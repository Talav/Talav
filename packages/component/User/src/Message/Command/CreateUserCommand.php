<?php

declare(strict_types=1);

namespace Talav\Component\User\Message\Command;

use Talav\Component\Resource\Model\DomainEventInterface;
use Talav\Component\User\Message\Dto\CreateUserDto;

final class CreateUserCommand implements DomainEventInterface
{
    public function __construct(
        public CreateUserDto $dto
    ) {
    }
}
