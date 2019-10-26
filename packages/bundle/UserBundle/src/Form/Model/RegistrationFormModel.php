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
     *
     * @var string|null
     */
    private $email;

    /**
     * @Assert\NotBlank(message="talav.username.blank")
     * @RegisteredUser(message="talav.username.already_used", field="username")
     *
     * @var string|null
     */
    private $username;

    /**
     * @Assert\NotBlank(message="talav.password.blank")
     * @Assert\Length(min=4, minMessage="talav.password.short", max=254, maxMessage="talav.password.long")
     *
     * @var string|null
     */
    private $plainPassword;

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }
}
