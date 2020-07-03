<?php

declare(strict_types=1);

namespace Talav\UserBundle\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LoginFormAuthenticator extends AbstractFormLoginAuthenticator
{
    use TargetPathTrait;

    /** @var RouterInterface */
    private $router;

    /** @var CsrfTokenManagerInterface */
    private $csrfTokenManager;

    /** @var EncoderFactoryInterface */
    private $encoderFactory;

    /** @var array */
    private $parameters;

    /**
     * LoginFormAuthenticator constructor.
     */
    public function __construct(
        RouterInterface $router,
        CsrfTokenManagerInterface $csrfTokenManager,
        EncoderFactoryInterface $encoderFactory,
        array $parameters
    )
    {
        $this->router = $router;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->encoderFactory = $encoderFactory;
        $this->parameters = $parameters;
    }

    public function supports(Request $request)
    {
        return 'talav_user_login' === $request->attributes->get('_route')
            && $request->isMethod('POST');
    }

    public function getCredentials(Request $request)
    {
        $credentials = [
            'username' => $request->request->get('_username'),
            'password' => $request->request->get('_password'),
            'csrf_token' => $request->request->get('_csrf_token'),
        ];
        $request->getSession()->set(
            Security::LAST_USERNAME,
            $credentials['username']
        );

        return $credentials;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $token = new CsrfToken('authenticate', $credentials['csrf_token']);
        if (!$this->csrfTokenManager->isTokenValid($token)) {
            throw new InvalidCsrfTokenException();
        }
        $user = $userProvider->loadUserByUsername($credentials['username']);
        if (!$user) {
            // fail authentication with a custom error
            throw new CustomUserMessageAuthenticationException('User could not be found.');
        }

        return $user;
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return $this->encoderFactory->getEncoder($user)->isPasswordValid($user->getPassword(), $credentials['password'], $user->getSalt());
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $providerKey)) {
            return new RedirectResponse($targetPath);
        }
        return new RedirectResponse($this->router->generate($this->parameters['success_route']));
    }

    protected function getLoginUrl()
    {
        return $this->router->generate('talav_user_login');
    }
}
