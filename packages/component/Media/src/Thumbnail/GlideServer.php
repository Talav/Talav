<?php

declare(strict_types=1);

namespace Talav\Component\Media\Thumbnail;

use League\Glide\Server;
use Talav\Component\Media\Exception\FilesystemException;
use Talav\Component\Media\Model\MediaInterface;
use Talav\Component\Media\Provider\MediaProviderInterface;
use Talav\Component\Resource\Error\ErrorHandlerTrait;

final class GlideServer implements ThumbnailInterface
{
    use ErrorHandlerTrait;

    protected Server $server;

    protected ?string $tmpPath = null;

    protected ?string $tmpPrefix = null;

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
//        $tmp = $this->getTemporaryFile();
//        $imageData = $this->getImageData($provider->getFilesystemReference($media));
//        $this->disableErrorHandler();
//        if (false === file_put_contents($tmp, $imageData)) {
//            $this->restoreErrorHandler();
//            throw new FilesystemException('Unable to write temporary file');
//        }
//        $this->restoreErrorHandler();

        try {
            foreach ($provider->getFormats() as $options) {
                $options = $this->enforceExtension($options, $media);
                $this->server->makeImage($provider->getFilesystemReference($media), $options);
            }
        } catch (\Exception $e) {
            throw new FilesystemException('Could not generate image', 0, $e);
//        } finally {
//            if (file_exists($tmp)) {
//                if (!@unlink($tmp)) {
//                    throw new FilesystemException('Unable to clean up temporary file');
//                }
//            }
        }

//        $referenceFile = $provider->getFilesystemReference($media);
//
//        if (!$referenceFile->exists()) {
//            return;
//        }
//
//        foreach ($provider->getFormats() as $format => $settings) {
//            if (substr($format, 0, \strlen($media->getContext())) === $media->getContext() ||
//                MediaProviderInterface::FORMAT_ADMIN === $format) {
//                $resizer = (isset($settings['resizer']) && ($settings['resizer'])) ?
//                    $this->getResizer($settings['resizer']) :
//                    $provider->getResizer();
//                $resizer->resize(
//                    $media,
//                    $referenceFile,
//                    $provider->getFilesystem()->get($provider->generatePrivateUrl($media, $format), true),
//                    $this->getExtension($media),
//                    $settings
//                );
//            }
//        }
    }

    public function isThumbExists(MediaProviderInterface $provider, MediaInterface $media, array $options): bool
    {
        $options = $this->enforceExtension($options, $media);

        return $this->server->cacheFileExists($provider->getFilesystemReference($media), $options);
    }

    protected function getImageData(string $reference): string
    {
        if ($this->server->getSource()->fileExists($reference)) {
            return $this->server->getSource()->read($reference);
        }
        throw new FilesystemException('File not found');
    }

    protected function getTemporaryFile(): string
    {
        if (empty($this->tmpPath)) {
            $this->tmpPath = sys_get_temp_dir();
        }
        if (empty($this->tmpPrefix)) {
            $this->tmpPrefix = 'media';
        }

        $this->disableErrorHandler();
        $tempFile = tempnam($this->tmpPath, $this->tmpPrefix);
        $this->restoreErrorHandler();

        return $tempFile;
    }

    protected function enforceExtension(array $options, MediaInterface $media): array
    {
        $options['fm'] = $options['fm'] ?? $media->getFileExtension();

        return $options;
    }
}
