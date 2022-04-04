<?php

declare(strict_types=1);

namespace UserAppBundle\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Talav\UserBundle\Entity\UserOAuth as BaseUserOAuth;

#[Entity]
#[Table(name: 'test_user_oauth')]
class UserOAuth extends BaseUserOAuth
{
    #[Id]
    #[Column(type: 'integer')]
    #[GeneratedValue(strategy: 'AUTO')]
    protected $id = null;
}
