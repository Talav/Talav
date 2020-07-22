<?php

declare(strict_types=1);

namespace Talav\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Talav\UserBundle\Form\DataTransformer\UserToUsernameTransformer;

/**
 * Form type for representing a UserInterface instance by its username string.
 */
class UsernameFormType extends AbstractType
{
    protected UserToUsernameTransformer $usernameTransformer;

    public function __construct(UserToUsernameTransformer $usernameTransformer)
    {
        $this->usernameTransformer = $usernameTransformer;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addModelTransformer($this->usernameTransformer);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(): string
    {
        return TextType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix(): string
    {
        return 'talav_user_username';
    }
}
