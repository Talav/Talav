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
use Talav\Component\Media\Manager\MediaManager;
use Talav\Component\Media\Provider\ProviderPool;
use Talav\MediaBundle\Form\DataTransformer\ProviderDataTransformer;

class MediaType extends AbstractType
{
    /** @var ProviderPool */
    protected $pool;

    /** @var MediaManager */
    protected $manager;

    public function __construct(ProviderPool $pool, MediaManager $manager)
    {
        $this->pool = $pool;
        $this->manager = $manager;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
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

        $builder->add('file', FileType::class, [
            'required' => false,
            'label' => 'talav.media.form.file',
            'translation_domain' => 'TalavMediaBundle',
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
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['provider'] = $options['provider'];
        $view->vars['context'] = $options['context'];
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
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
    public function getParent()
    {
        return FormType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'talav_media_type';
    }
}
