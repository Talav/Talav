<?php

declare(strict_types=1);

namespace Talav\Component\User\Security;

use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Talav\Component\User\Model\CredentialsHolderInterface;

class PasswordUpdater implements PasswordUpdaterInterface
{
    /** @var EncoderFactoryInterface */
    private $encoderFactory;

    /**
     * PasswordUpdater constructor.
     */
    public function __construct(EncoderFactoryInterface $encoderFactory)
    {
        $this->encoderFactory = $encoderFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function updatePassword(CredentialsHolderInterface $user): void
    {
        $encoder = $this->encoderFactory->getEncoder($user);
        if (!empty($user->getPlainPassword())) {
            $user->setPassword($encoder->encodePassword($user->getPlainPassword(), $user->getSalt()));
            $user->eraseCredentials();
        }
    }
}
