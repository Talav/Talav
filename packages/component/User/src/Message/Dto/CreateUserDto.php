<?php

declare(strict_types=1);

namespace Talav\Component\User\Message\Dto;

use Talav\Component\Resource\Model\DomainEventInterface;

final class CreateUserDto implements DomainEventInterface
{
    public function __construct(
        public string $username,
        public string $email,
        public string $password,
        public bool $active = true,
        public ?string $firstName = null,
        public ?string $lastName = null
    ) {
    }
}
