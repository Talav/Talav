<?php

declare(strict_types=1);

namespace Talav\UserBundle\Form\Model;

use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints as Assert;

class ChangePasswordModel
{
    /** @UserPassword(message="talav.current_password.invalid") */
    private ?string $currentPassword = null;

    /**
     * @Assert\NotBlank(message="talav.password.blank")
     * @Assert\Length(min=4, minMessage="talav.password.short", max=254, maxMessage="talav.password.long")
     */
    private ?string $newPassword = null;

    public function getCurrentPassword(): ?string
    {
        return $this->currentPassword;
    }

    public function setCurrentPassword(?string $currentPassword): void
    {
        $this->currentPassword = $currentPassword;
    }

    public function getNewPassword(): ?string
    {
        return $this->newPassword;
    }

    public function setNewPassword(?string $newPassword): void
    {
        $this->newPassword = $newPassword;
    }
}
