<?php

declare(strict_types=1);

namespace Talav\Component\User\Manager;

use Talav\Component\Resource\Manager\ManagerInterface;
use Talav\Component\User\Model\UserOAuthInterface;

interface UserOAuthManagerInterface extends ManagerInterface
{
    public function findOneByProviderIdentifier(string $provider, string $identifier): ?UserOAuthInterface;
}
