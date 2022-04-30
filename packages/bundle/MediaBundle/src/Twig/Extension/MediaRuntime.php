<?php

declare(strict_types=1);

namespace Talav\MediaBundle\Twig\Extension;

use Talav\Component\Media\Model\MediaInterface;
use Talav\Component\Media\Provider\ProviderPool;
use Twig\Environment;
use Twig\Extension\RuntimeExtensionInterface;

final class MediaRuntime implements RuntimeExtensionInterface
{
    private ProviderPool $pool;

    private Environment $twig;

    public function __construct(
        ProviderPool $pool,
        Environment $twig
    ) {
        $this->pool = $pool;
        $this->twig = $twig;
    }

    public function mediaReference(MediaInterface $media): string
    {
        $provider = $this->pool->getProvider($media->getProviderName());

        return $provider->getPublicUrl($media);
    }

    public function thumbReference(MediaInterface $media, string $formatName): string
    {
        $provider = $this->pool->getProvider($media->getProviderName());

        return $provider->getThumbnailPublicUrl($media, $formatName);
    }

    public function thumb(MediaInterface $media, string $formatName, ?iterable $options = []): string
    {
        $provider = $this->pool->getProvider($media->getProviderName());
        $template = $provider->getTemplateConfig()->getThumb();

        $options = array_merge($provider->getViewHelperProperties($media, $formatName, $options), $options);

        return $this->twig->render($template, [
            'media' => $media,
            'options' => $options,
        ]);
    }

    public function media($media, string $formatName, ?iterable $options = []): string
    {
        $provider = $this->pool->getProvider($media->getProviderName());
        $template = $provider->getTemplateConfig()->getView();
        $options = $provider->getViewHelperProperties($media, $formatName, $options);

        return $this->twig->render($template, [
            'media' => $media,
            'format' => $formatName,
            'options' => $options,
        ]);
    }
}
