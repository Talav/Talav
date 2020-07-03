<?php

declare(strict_types=1);

namespace Talav\UserBundle\Mailer;

use Talav\Component\User\Model\UserInterface;

interface UserMailerInterface
{
    /**
     * Send an email to a user to confirm the account creation.
     */
    public function sendConfirmationEmailMessage(UserInterface $user): void;

    /**
     * Send an email to a user to confirm the password reset.
     */
    public function sendResettingEmailMessage(UserInterface $user): void;

    /**
     * Send an email to a user to confirm registration success, welcome email
     */
    public function sendRegistrationSuccessfulEmail(UserInterface $user): void;
}
