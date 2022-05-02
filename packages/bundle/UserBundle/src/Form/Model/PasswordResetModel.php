<?php

declare(strict_types=1);

namespace Talav\UserBundle\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;

final class PasswordResetModel
{
    /**
     * @Assert\NotBlank(message="talav.password.blank")
     * @Assert\Length(min=4, minMessage="talav.password.short", max=254, maxMessage="talav.password.long")
     */
    public ?string $password = null;
}
