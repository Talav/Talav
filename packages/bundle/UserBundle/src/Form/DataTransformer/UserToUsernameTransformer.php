<?php

declare(strict_types=1);

namespace Talav\UserBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Talav\Component\User\Manager\UserManagerInterface;
use Talav\Component\User\Model\UserInterface;

/**
 * Transforms between a UserInterface instance and a username string.
 */
class UserToUsernameTransformer implements DataTransformerInterface
{
    protected UserManagerInterface $userManager;

    public function __construct(UserManagerInterface $userManager)
    {
        $this->userManager = $userManager;
    }

    /**
     * Transforms a UserInterface instance into a username string.
     *
     * @param UserInterface|null $value UserInterface instance
     *
     * @return string|null Username
     *
     * @throws UnexpectedTypeException if the given value is not a UserInterface instance
     */
    public function transform($value): ?string
    {
        if (null === $value) {
            return null;
        }
        if (!$value instanceof UserInterface) {
            throw new UnexpectedTypeException($value, UserInterface::class);
        }

        return $value->getUsername();
    }

    /**
     * Transforms a username string into a UserInterface instance.
     *
     * @param string $value Username
     *
     * @return UserInterface the corresponding UserInterface instance
     *
     * @throws UnexpectedTypeException if the given value is not a string
     */
    public function reverseTransform($value): ?UserInterface
    {
        if (null === $value || '' === $value) {
            return null;
        }
        if (!is_string($value)) {
            throw new UnexpectedTypeException($value, 'string');
        }

        return $this->userManager->findUserByUsernameOrEmail($value);
    }
}
