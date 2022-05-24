<?php

declare(strict_types=1);

namespace Talav\Component\User\Mapper\Dto;

use AutoMapperPlus\CustomMapper\CustomMapper;
use Talav\Component\User\Message\Dto\CreateUserDto;
use Talav\Component\User\Model\UserInterface;
use Webmozart\Assert\Assert;

final class CreateUserMapper extends CustomMapper
{
    public function mapToObject($source, $destination)
    {
        /* @var CreateUserDto $source */
        Assert::isInstanceOf($source, CreateUserDto::class);
        /* @var UserInterface $destination */
        Assert::isInstanceOf($destination, UserInterface::class);

        $destination->setUsername($source->username);
        $destination->setEmail($source->email);
        $destination->setEnabled($source->active);
        $destination->setFirstName($source->firstName);
        $destination->setLastName($source->lastName);

        return $destination;
    }
}
