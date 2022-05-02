<?php

declare(strict_types=1);

namespace Talav\UserBundle\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;
use Talav\Component\User\Model\UserInterface;

final class RequestPasswordResetModel
{
    /** @Assert\NotBlank(message="talav.username.not_found") */
    public ?UserInterface $user = null;
}
