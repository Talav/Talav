<?php

declare(strict_types=1);

namespace Talav\UserBundle\Entity;

use Talav\Component\Resource\Model\ResourceTrait;
use Talav\Component\User\Model\UserAwareTrait;
use Talav\Component\User\Model\UserOAuthInterface;

abstract class UserOAuth implements UserOAuthInterface
{
    use ResourceTrait;
    use UserAwareTrait;

    /**
     * OAuth access token.
     *
     * @var string|null
     */
    protected $accessToken;

    /**
     * OAuth identifier.
     *
     * @var string|null
     */
    protected $identifier;

    /**
     * OAuth provider name.
     *
     * @var string|null
     */
    protected $provider;

    /**
     * OAuth refresh token.
     *
     * @var string|null
     */
    protected $refreshToken;

    public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }

    public function setAccessToken(?string $accessToken): void
    {
        $this->accessToken = $accessToken;
    }

    public function getIdentifier(): ?string
    {
        return $this->identifier;
    }

    public function setIdentifier(?string $identifier): void
    {
        $this->identifier = $identifier;
    }

    public function getProvider(): ?string
    {
        return $this->provider;
    }

    public function setProvider(?string $provider): void
    {
        $this->provider = $provider;
    }

    public function getRefreshToken(): ?string
    {
        return $this->refreshToken;
    }

    public function setRefreshToken(?string $refreshToken): void
    {
        $this->refreshToken = $refreshToken;
    }
}
