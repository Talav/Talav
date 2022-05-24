<?php

declare(strict_types=1);

namespace Talav\Component\User\Tests\Unit\Mapper\Dto;

use PHPUnit\Framework\TestCase;
use Talav\Component\User\Mapper\Dto\CreateUserMapper;
use Talav\Component\User\Message\Dto\CreateUserDto;
use Talav\Component\User\Tests\Unit\Setup\Entity\User;

final class CreateUserMapperTest extends TestCase
{
    /**
     * @test
     */
    public function it_maps_all_fields()
    {
        $dto = new CreateUserDto(
            'username',
            'email',
            'password',
            false,
            'first_name',
            'last_name'
        );
        $user = new User();
        $mapper = new CreateUserMapper();
        $mapper->mapToObject($dto, $user);
        self::assertEquals($dto->username, $user->getUsername());
        self::assertEquals($dto->email, $user->getEmail());
        self::assertEquals($dto->active, $user->isEnabled());
        self::assertEquals($dto->firstName, $user->getFirstName());
        self::assertEquals($dto->lastName, $user->getLastName());
        self::assertNull($user->getPassword());
    }
}
