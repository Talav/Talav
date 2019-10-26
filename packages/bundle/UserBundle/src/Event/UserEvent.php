<?php

declare(strict_types=1);

namespace Talav\UserBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Talav\Component\User\Model\UserInterface;

class UserEvent extends Event
{
    /** @var UserInterface */
    private $user;

    public function __construct(UserInterface $user)
    {
        $this->user = $user;
    }

    public function getUser(): UserInterface
    {
        return $this->user;
    }
}
