<?php

declare(strict_types=1);

namespace Talav\Component\User\Mapper\Configurator;

use AutoMapperPlus\AutoMapperPlusBundle\AutoMapperConfiguratorInterface;
use AutoMapperPlus\Configuration\AutoMapperConfigInterface;
use Talav\Component\User\Mapper\Dto\CreateUserMapper;
use Talav\Component\User\Message\Dto\CreateUserDto;
use Talav\Component\User\Model\UserInterface;

class UserConfigurator implements AutoMapperConfiguratorInterface
{
    public function configure(AutoMapperConfigInterface $config): void
    {
        $config->registerMapping(CreateUserDto::class, UserInterface::class)
            ->useCustomMapper(new CreateUserMapper());
    }
}
