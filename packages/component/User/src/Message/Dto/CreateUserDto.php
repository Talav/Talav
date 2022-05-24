<?php

declare(strict_types=1);

namespace Talav\Component\User\Message\Dto;

use Talav\Component\Resource\Model\DomainEventInterface;

// @todo, find a better approach to create DTO
final class CreateUserDto implements DomainEventInterface
{
    public bool $active = true;
    public ?string $firstName = null;
    public ?string $lastName = null;

    public function __construct(
        public string $username,
        public string $email,
        public string $password,
        bool $active = true,
        ?string $firstName = null,
        ?string $lastName = null
    ) {
        $this->active = $active;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }
}
