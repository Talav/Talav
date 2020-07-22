<?php

declare(strict_types=1);

namespace Talav\UserBundle\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;
use Talav\UserBundle\Validator\Constraints\RegisteredUser;

final class RegistrationFormModel
{
    /**
     * @Assert\NotBlank(message="talav.email.blank")
     * @Assert\Email(message="talav.email.invalid", mode="strict")
     * @RegisteredUser(message="talav.email.already_used")
     */
    private ?string $email = null;

    /**
     * @Assert\NotBlank(message="talav.username.blank")
     * @RegisteredUser(message="talav.username.already_used", field="username")
     */
    private ?string $username = null;

    /**
     * @Assert\NotBlank(message="talav.password.blank")
     * @Assert\Length(min=4, minMessage="talav.password.short", max=254, maxMessage="talav.password.long")
     */
    private ?string $plainPassword = null;

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): void
    {
        $this->username = $username;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }
}
