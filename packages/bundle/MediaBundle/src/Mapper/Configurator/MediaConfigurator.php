<?php

declare(strict_types=1);

namespace Talav\MediaBundle\Mapper\Configurator;

use AutoMapperPlus\AutoMapperPlusBundle\AutoMapperConfiguratorInterface;
use AutoMapperPlus\Configuration\AutoMapperConfigInterface;
use Talav\Component\Media\Message\Dto\Media\CreateMediaDto;
use Talav\Component\Media\Message\Dto\Media\UpdateMediaDto;
use Talav\Component\Media\Model\MediaInterface;
use Talav\MediaBundle\Form\Model\MediaModel;
use Talav\MediaBundle\Mapper\Command\ProcessToCreateMediaMapper;
use Talav\MediaBundle\Mapper\Command\ProcessToUpdateMediaMapper;
use Talav\MediaBundle\Mapper\FormModel\MediaToMediaModelMapper;
use Talav\MediaBundle\Message\Dto\ProcessMediaDto;

class MediaConfigurator implements AutoMapperConfiguratorInterface
{
    public function configure(AutoMapperConfigInterface $config): void
    {
        $config->registerMapping(MediaInterface::class, MediaModel::class)
            ->useCustomMapper(new MediaToMediaModelMapper());
        $config->registerMapping(ProcessMediaDto::class, CreateMediaDto::class)
            ->useCustomMapper(new ProcessToCreateMediaMapper());
        $config->registerMapping(ProcessMediaDto::class, UpdateMediaDto::class)
            ->useCustomMapper(new ProcessToUpdateMediaMapper());
    }
}
