<?php

declare(strict_types=1);

namespace Talav\MediaBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Constraint;
use Talav\Component\Media\Provider\ProviderPool;
use Talav\MediaBundle\Form\DataTransformer\ProviderDataTransformer;
use Talav\MediaBundle\Form\Model\MediaModel;

class MediaType extends AbstractType
{
    protected ProviderPool $pool;

    public function __construct(ProviderPool $pool)
    {
        $this->pool = $pool;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $dataTransformer = new ProviderDataTransformer([
            'provider' => $options['provider'],
            'context' => $options['context'],
            'delete_on_unlink' => $options['delete_on_unlink'],
        ]);
        $builder->addModelTransformer($dataTransformer);

        $required = $options['required'] ?? false;

        $provider = $this->pool->getProvider($options['provider']);
        $constraints = $provider->getFileFieldConstraints();
        if ($required) {
            $constraints[] = new Constraint\NotBlank();
        }

        $builder->add('file', FileType::class, [
            'required' => $required,
            'label' => 'talav.media.form.file',
            'translation_domain' => 'TalavMediaBundle',
            'constraints' => $constraints,
        ]);

        $builder->add('unlink', CheckboxType::class, [
            'label' => 'talav.media.form.unlink',
            'translation_domain' => 'TalavMediaBundle',
            'required' => false,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'provider' => null,
                'context' => null,
                // defines if existing media should be deleted after it's unlinked
                'delete_on_unlink' => true,
                'data_class' => MediaModel::class,
                'translation_domain' => 'TalavMediaBundle',
            ]);
        $resolver->setAllowedValues('provider', array_keys($this->pool->getProviderList()));
        $resolver->setAllowedValues('context', array_keys($this->pool->getContexts()));
    }
}
