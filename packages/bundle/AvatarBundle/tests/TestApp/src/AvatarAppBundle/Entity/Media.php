<?php

declare(strict_types=1);

namespace AvatarAppBundle\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Talav\MediaBundle\Entity\Media as BaseMedia;

#[Entity]
#[Table(name: 'test_media')]
class Media extends BaseMedia
{
    #[Id]
    #[Column(type: 'integer')]
    #[GeneratedValue(strategy: 'AUTO')]
    protected $id = null;
}
