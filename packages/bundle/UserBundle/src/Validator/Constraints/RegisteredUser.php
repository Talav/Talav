<?php

declare(strict_types=1);

namespace Talav\UserBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
final class RegisteredUser extends Constraint
{
    public string $message = 'User with this property is already registered. Please log in.';

    public string $field = 'email';

    public ?string $idToSkip = null;

    public function validatedBy(): string
    {
        return \get_class($this).'Validator';
    }
}
