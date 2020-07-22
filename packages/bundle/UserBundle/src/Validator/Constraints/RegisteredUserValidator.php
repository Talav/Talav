<?php

declare(strict_types=1);

namespace Talav\UserBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Talav\Component\User\Manager\UserManagerInterface;

final class RegisteredUserValidator extends ConstraintValidator
{
    private UserManagerInterface $userManager;

    public function __construct(UserManagerInterface $userManager)
    {
        $this->userManager = $userManager;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($field, Constraint $constraint): void
    {
        if (!$constraint instanceof RegisteredUser) {
            throw new UnexpectedTypeException($constraint, RegisteredUser::class);
        }
        if (null === $field) {
            return;
        }
        if ('email' == $constraint->field) {
            $existingUser = $this->userManager->findUserByEmail($field);
        } else {
            $existingUser = $this->userManager->findUserByUsername($field);
        }
        if (null !== $existingUser) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}
