<?php

declare(strict_types=1);

namespace Talav\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

final class RequestPasswordResetType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('user', UsernameFormType::class, [
                'label' => 'talav.form.username_email',
                'translation_domain' => 'TalavUserBundle',
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix(): string
    {
        return 'talav_user_reset_password_request';
    }
}
