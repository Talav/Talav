<?php

declare(strict_types=1);

namespace Talav\UserBundle\Form\Model;

use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints as Assert;

class ChangePasswordModel
{
    /** @UserPassword(message="talav.current_password.invalid") */
    public ?string $currentPassword = null;

    /**
     * @Assert\NotBlank(message="talav.password.blank")
     * @Assert\Length(min=4, minMessage="talav.password.short", max=254, maxMessage="talav.password.long")
     */
    public ?string $newPassword = null;
}
