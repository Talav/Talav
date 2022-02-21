<?php

declare(strict_types=1);

namespace MediaAppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Talav\MediaBundle\Form\Type\MediaType;

class AuthorForm extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, ['label' => 'Name'])
            ->add('media', MediaType::class, [
                'label' => 'Media',
                'provider' => 'file',
                'context' => 'doc',
                'required' => false,
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'talav_media_app_entity';
    }
}
