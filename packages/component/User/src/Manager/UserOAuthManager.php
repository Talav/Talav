<?php

declare(strict_types=1);

namespace Talav\Component\User\Manager;

use Talav\Component\Resource\Manager\ResourceManager;
use Talav\Component\User\Model\UserOAuthInterface;

class UserOAuthManager extends ResourceManager implements UserOAuthManagerInterface
{
    public function findOneByProviderIdentifier(string $provider, string $identifier): ?UserOAuthInterface
    {
        return$this->getRepository()->findOneBy([
            'provider' => $provider,
            'identifier' => $identifier,
        ]);
    }
}
