<?php

declare(strict_types=1);

namespace Talav\UserBundle\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;
use Talav\Component\User\Model\UserInterface;

final class RequestPasswordResetModel
{
    /**
     * @Assert\NotBlank(message="talav.username.not_found")
     *
     * @var UserInterface|null
     */
    private $user;

    public function getUser(): ?UserInterface
    {
        return $this->user;
    }

    public function setUser(?UserInterface $user): void
    {
        $this->user = $user;
    }
}
