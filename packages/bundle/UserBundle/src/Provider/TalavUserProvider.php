<?php

declare(strict_types=1);

namespace Talav\UserBundle\Provider;

use HWI\Bundle\OAuthBundle\Connect\AccountConnectorInterface;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthAwareUserProviderInterface;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Talav\Component\User\Manager\UserManagerInterface;
use Talav\Component\User\Manager\UserOAuthManagerInterface;
use Talav\Component\User\Model\UserInterface as TalavUserInterface;
use Talav\Component\User\Model\UserOAuthInterface;
use Talav\Component\User\Provider\UserProvider;
use Webmozart\Assert\Assert;

/**
 * Class providing a bridge to use the Talav user provider with HWIOAuth.
 */
class TalavUserProvider extends UserProvider implements UserProviderInterface, AccountConnectorInterface, OAuthAwareUserProviderInterface
{
    protected UserManagerInterface $userManager;

    protected UserOAuthManagerInterface $userOAuthManager;

    public function __construct(UserManagerInterface $userManager, UserOAuthManagerInterface $userOAuthManager)
    {
        $this->userManager = $userManager;
        $this->userOAuthManager = $userOAuthManager;
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response): UserInterface
    {
        $oauth = $this->userOAuthManager->findOneByProviderIdentifier(
            $response->getResourceOwner()->getName(),
            $response->getUsername()
        );
        if ($oauth instanceof UserOAuthInterface) {
            return $oauth->getUser();
        }
        if (null !== $response->getEmail()) {
            $user = $this->userManager->findUserByUsernameOrEmail($response->getEmail());
            if (null !== $user) {
                return $this->updateUserByOAuthUserResponse($user, $response);
            }

            return $this->createUserByOAuthUserResponse($response);
        }

        throw new UserNotFoundException('Email is null or not provided');
    }

    /**
     * {@inheritdoc}
     */
    public function connect(UserInterface $user, UserResponseInterface $response): void
    {
        $this->updateUserByOAuthUserResponse($user, $response);
    }

    /**
     * Ad-hoc creation of user.
     */
    private function createUserByOAuthUserResponse(UserResponseInterface $response): TalavUserInterface
    {
        /** @var TalavUserInterface $user */
        $user = $this->userManager->create();

        // set default values taken from OAuth sign-in provider account
        $user->setEmail($response->getEmail());
        if (null !== $name = $response->getFirstName()) {
            $user->setFirstName($name);
        } elseif (null !== $realName = $response->getRealName()) {
            $user->setFirstName($realName);
        }
        if (null !== $lastName = $response->getLastName()) {
            $user->setLastName($lastName);
        }
        if (!$user->getUsername()) {
            $user->setUsername($this->generateRandomUsername($response->getResourceOwner()->getName()));
        }

        // set random password to prevent issue with not nullable field & potential security hole
        $user->setPassword(substr(sha1($response->getAccessToken()), 0, 30));
        $user->setEnabled(true);

        return $this->updateUserByOAuthUserResponse($user, $response);
    }

    /**
     * Attach OAuth sign-in provider account to existing user.
     */
    private function updateUserByOAuthUserResponse(
        TalavUserInterface $user,
        UserResponseInterface $response
    ): TalavUserInterface {
        Assert::isInstanceOf($user, TalavUserInterface::class);

        /** @var UserOAuthInterface $oauth */
        $oauth = $this->userOAuthManager->create();
        $oauth->setIdentifier($response->getUsername());
        $oauth->setProvider($response->getResourceOwner()->getName());
        $oauth->setAccessToken($response->getAccessToken());
        $oauth->setRefreshToken($response->getRefreshToken());

        $user->addOAuthAccount($oauth);
        $this->userManager->update($user, true);

        return $user;
    }

    /**
     * Generates a random username with the given
     * e.g github_user12345, facebook_user12345.
     *
     * @param string $serviceName
     */
    private function generateRandomUsername($serviceName): string
    {
        return $serviceName.'_'.substr(uniqid((rand()), true), 10);
    }
}
