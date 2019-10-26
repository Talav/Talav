<?php

declare(strict_types=1);

namespace Talav\UserBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
final class RegisteredUser extends Constraint
{
    public $message = 'User with this property is already registered. Please log in.';

    public $field = 'email';

    /**
     * {@inheritdoc}
     */
    public function validatedBy()
    {
        return \get_class($this) . 'Validator';
    }
}
