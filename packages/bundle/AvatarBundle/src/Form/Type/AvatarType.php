<?php

declare(strict_types=1);

namespace Talav\AvatarBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Talav\MediaBundle\Form\Type\MediaType;

class AvatarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->enforceContext($options);
        $builder
            ->add('avatar', MediaType::class, [
                'label' => 'talav.update_avatar.form.avatar',
                'translation_domain' => 'TalavAvatarBundle',
                'provider' => $options['provider'],
                'context' => $options['context'],
                'required' => false,
            ])
        ;
    }

    private function enforceContext(array $options): void
    {
        if (!isset($options['provider'])) {
            throw new \RuntimeException('Provider is not set');
        }
        if (!isset($options['context'])) {
            throw new \RuntimeException('Context is not set');
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setRequired(['provider', 'context'])
            ->setAllowedTypes('provider', 'string')
            ->setAllowedTypes('context', 'string');
    }

    public function getBlockPrefix()
    {
        return 'talav_avatar_type';
    }
}
