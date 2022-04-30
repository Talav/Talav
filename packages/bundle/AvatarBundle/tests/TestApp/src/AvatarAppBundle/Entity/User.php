<?php

declare(strict_types=1);

namespace AvatarAppBundle\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\Table;
use Talav\AvatarBundle\Model\UserAvatarInterface;
use Talav\Component\Media\Model\MediaInterface;
use Talav\UserBundle\Entity\User as BaseUser;

#[Entity]
#[Table(name: 'test_user')]
class User extends BaseUser implements UserAvatarInterface
{
    #[Id]
    #[Column(type: 'integer')]
    #[GeneratedValue(strategy: 'AUTO')]
    protected $id = null;

    #[OneToOne(targetEntity: "Talav\Component\Media\Model\MediaInterface", cascade: ['persist'])]
    #[JoinColumn(name: 'media_id')]
    protected ?MediaInterface $avatar = null;

    public function getAvatar(): ?MediaInterface
    {
        return $this->avatar;
    }

    public function setAvatar(?MediaInterface $avatar): void
    {
        $this->avatar = $avatar;
    }

    public function getAvatarName(): ?string
    {
        return $this->getFirstName().' '.$this->getLastName();
    }

    public function getAvatarDescription(): ?string
    {
        return $this->getAvatarName();
    }
}
