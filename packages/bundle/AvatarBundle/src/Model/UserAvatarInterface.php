<?php

declare(strict_types=1);

namespace Talav\AvatarBundle\Model;

use Talav\Component\Media\Model\MediaInterface;

interface UserAvatarInterface
{
    public function getAvatar(): ?MediaInterface;

    public function setAvatar(?MediaInterface $avatar): void;
}
