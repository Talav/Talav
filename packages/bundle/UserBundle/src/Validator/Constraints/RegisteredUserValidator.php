<?php

declare(strict_types=1);

namespace Talav\UserBundle\Validator\Constraints;

use Symfony\Component\PropertyAccess\Exception\NoSuchPropertyException;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Talav\Component\User\Manager\UserManagerInterface;

final class RegisteredUserValidator extends ConstraintValidator
{
    public function __construct(
        private readonly UserManagerInterface $userManager,
        private readonly PropertyAccessorInterface $propertyAccessor
    ) {
    }

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

    private function getId(Constraint $constraint)
    {
        if ($path = $constraint->propertyPath) {
            if (null === $object = $this->context->getObject()) {
                return;
            }

            try {
                $comparedValue = $this->getPropertyAccessor()->getValue($object, $path);
            } catch (NoSuchPropertyException $e) {
                throw new ConstraintDefinitionException(sprintf('Invalid property path "%s" provided to "%s" constraint: ', $path, get_debug_type($constraint)).$e->getMessage(), 0, $e);
            }
        } else {
            $comparedValue = $constraint->value;
        }
    }
}
