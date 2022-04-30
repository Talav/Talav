<?php

declare(strict_types=1);

namespace Talav\MediaBundle\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class MediaExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('media_media_reference', [MediaRuntime::class, 'mediaReference']),
            new TwigFunction('media_thumb_reference', [MediaRuntime::class, 'thumbReference']),
            new TwigFunction('media_thumb', [MediaRuntime::class, 'thumb'], ['is_safe' => ['html']]),
            new TwigFunction('media_media', [MediaRuntime::class, 'media'], ['is_safe' => ['html']]),
        ];
    }
}
