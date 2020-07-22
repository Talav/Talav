<?php

declare(strict_types=1);

namespace Talav\UserBundle\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;
use Talav\Component\User\Model\UserAwareTrait;
use Talav\Component\User\Model\UserInterface;

final class RequestPasswordResetModel
{
    use UserAwareTrait;

    /** @Assert\NotBlank(message="talav.username.not_found") */
    protected ?UserInterface $user = null;
}
