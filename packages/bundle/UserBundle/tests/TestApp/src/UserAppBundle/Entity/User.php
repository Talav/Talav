<?php

declare(strict_types=1);

namespace UserAppBundle\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Talav\UserBundle\Entity\User as BaseUser;

#[Entity]
#[Table(name: 'test_user')]
class User extends BaseUser
{
    #[Id]
    #[Column(type: 'integer')]
    #[GeneratedValue(strategy: 'AUTO')]
    protected mixed $id = null;
}
