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
    public ?string $username = null;
    public ?string $email = null;
    public ?string $password = null;
}
