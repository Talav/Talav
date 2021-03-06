<?php

declare(strict_types=1);

namespace Talav\MediaBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Constraint;
use Talav\Component\Media\Manager\MediaManager;
use Talav\Component\Media\Provider\ProviderPool;
use Talav\MediaBundle\Form\DataTransformer\ProviderDataTransformer;

class MediaType extends AbstractType
{
    protected ProviderPool $pool;

    protected MediaManager $manager;

    public function __construct(ProviderPool $pool, MediaManager $manager)
    {
        $this->pool = $pool;
        $this->manager = $manager;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $dataTransformer = new ProviderDataTransformer($this->pool, $this->manager, [
            'provider' => $options['provider'],
            'context' => $options['context'],
        ]);
        $builder->addModelTransformer($dataTransformer);

        $builder->addEventListener(FormEvents::SUBMIT, static function (FormEvent $event) {
            if ($event->getForm()->has('unlink') && $event->getForm()->get('unlink')->getData()) {
                $event->setData(null);
            }
        });

        $required = $options['required'] ?? false;

        $constraints = $this->pool->getProvider($options['provider'])->getFileFieldConstraints();
        if ($required) {
            $constraints[] = new Constraint\NotBlank();
        }

        $builder->add('file', FileType::class, [
            'required' => $options['required'] ?? false,
            'label' => 'talav.media.form.file',
            'translation_domain' => 'TalavMediaBundle',
            'constraints' => $constraints,
        ]);

        $builder->add('unlink', CheckboxType::class, [
            'label' => 'talav.media.form.unlink',
            'mapped' => false,
            'data' => false,
            'required' => false,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['provider'] = $options['provider'];
        $view->vars['context'] = $options['context'];
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'data_class' => $this->manager->getClassName(),
                'translation_domain' => 'TalavMediaBundle',
            ])
            ->setRequired(['provider', 'context'])
            ->setAllowedTypes('provider', 'string')
            ->setAllowedTypes('context', 'string')
            ->setAllowedValues('provider', $this->pool->getProviderList())
            ->setAllowedValues('context', array_keys($this->pool->getContexts()));
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(): string
    {
        return FormType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix(): string
    {
        return 'talav_media_type';
    }
}
