<?php

declare(strict_types=1);

namespace Talav\Component\Media\Thumbnail;

use League\Glide\Server;
use Talav\Component\Media\Exception\FilesystemException;
use Talav\Component\Media\Model\MediaInterface;
use Talav\Component\Media\Provider\MediaProviderInterface;

final class GlideServer implements ThumbnailInterface
{
    protected Server $server;

    public function __construct(Server $server)
    {
        $this->server = $server;
        $this->server->setCacheWithFileExtensions(true);
    }

    public function generate(MediaProviderInterface $provider, MediaInterface $media): void
    {
        $filesystem = $provider->getFilesystem();
        $this->server->setSource($filesystem);
        $this->server->setCache($filesystem);

        try {
            foreach ($provider->getFormats() as $options) {
                $options = $this->enforceExtension($options, $media);
                $this->server->makeImage($provider->getFilesystemReference($media), $options);
            }
        } catch (\Exception $e) {
            throw new FilesystemException('Could not generate image', 0, $e);
        }
    }

    public function delete(MediaProviderInterface $provider, MediaInterface $media): void
    {
        $filesystem = $provider->getFilesystem();
        $this->server->setSource($filesystem);
        $this->server->setCache($filesystem);

        try {
            $this->server->deleteCache($provider->getFilesystemReference($media));
        } catch (\Exception $e) {
            throw new FilesystemException('Could not delete image', 0, $e);
        }
    }

    public function isThumbExists(MediaProviderInterface $provider, MediaInterface $media, array $options): bool
    {
        $options = $this->enforceExtension($options, $media);

        return $this->server->cacheFileExists($provider->getFilesystemReference($media), $options);
    }

    protected function enforceExtension(array $options, MediaInterface $media): array
    {
        $options['fm'] = $options['fm'] ?? $media->getFileExtension();

        return $options;
    }
}
