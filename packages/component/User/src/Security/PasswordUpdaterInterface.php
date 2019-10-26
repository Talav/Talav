<?php

declare(strict_types=1);

namespace Talav\Component\User\Security;

use Talav\Component\User\Model\CredentialsHolderInterface;

interface PasswordUpdaterInterface
{
    public function updatePassword(CredentialsHolderInterface $user): void;
}
