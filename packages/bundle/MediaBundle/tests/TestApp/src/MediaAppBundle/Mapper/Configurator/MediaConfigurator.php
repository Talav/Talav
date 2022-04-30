<?php

declare(strict_types=1);

namespace MediaAppBundle\Mapper\Configurator;

use AutoMapperPlus\AutoMapperPlusBundle\AutoMapperConfiguratorInterface;
use AutoMapperPlus\Configuration\AutoMapperConfigInterface;
use AutoMapperPlus\MappingOperation\Operation;
use MediaAppBundle\Entity\Author;
use MediaAppBundle\Form\Model\AuthorModel;
use Talav\MediaBundle\Form\Model\MediaModel;

class MediaConfigurator implements AutoMapperConfiguratorInterface
{
    public function configure(AutoMapperConfigInterface $config): void
    {
        $config->registerMapping(Author::class, AuthorModel::class)
            ->forMember('media', Operation::mapTo(MediaModel::class));
    }
}
