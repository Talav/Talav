<?php

declare(strict_types=1);

namespace Talav\MediaBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Talav\Component\Media\Manager\MediaManager;
use Talav\Component\Media\Model\MediaInterface;
use Talav\Component\Media\Provider\ProviderPool;

class ProviderDataTransformer implements DataTransformerInterface
{
    protected ProviderPool $pool;

    protected MediaManager $manager;

    protected array $options = [];

    public function __construct(ProviderPool $pool, MediaManager $manager, array $options = [])
    {
        $this->pool = $pool;
        $this->manager = $manager;
        $this->options = $this->getOptions($options);
    }

    /**
     * {@inheritdoc}
     */
    public function transform($value)
    {
        if (null === $value) {
            return $this->manager->create();
        }

        return $value;
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($media)
    {
        if (!$media instanceof MediaInterface) {
            return $media;
        }
        $file = $media->getFile();

        // no binary
        if (empty($file)) {
            // and no media id
            if (null === $media->getId()) {
                return;
            }

            return $media;
        }

        if (!$media->getProviderName() && $this->options['provider']) {
            $media->setProviderName($this->options['provider']);
        }

        if (!$media->getContext() && $this->options['context']) {
            $media->setContext($this->options['context']);
        }

        $provider = $this->pool->getProvider($media->getProviderName());

//        try {
//            $provider->transform($media);
//        } catch (\Throwable $e) {
//            // #1107 We must never throw an exception here.
//            // An exception here would prevent us to provide meaningful errors through the Form
//            // Error message inspired from Monolog\ErrorHandler
//            $this->logger->error(
//                sprintf('Caught Exception %s: "%s" at %s line %s', \get_class($e), $e->getMessage(), $e->getFile(), $e->getLine()),
//                ['exception' => $e]
//            );
//        }

        return $media;
    }

    /**
     * Define the default options for the DataTransformer.
     */
    protected function getOptions(array $options): array
    {
        return array_merge([
            'provider' => false,
            'context' => false,
        ], $options);
    }
}
