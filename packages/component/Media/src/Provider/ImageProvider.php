<?php

declare(strict_types=1);

namespace Talav\Component\Media\Provider;

use League\Flysystem\FilesystemOperator;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Talav\Component\Media\Cdn\CdnInterface;
use Talav\Component\Media\Generator\GeneratorInterface;
use Talav\Component\Media\Model\MediaInterface;
use Talav\Component\Media\Thumbnail\ThumbnailInterface;

class ImageProvider extends FileProvider
{
    protected ThumbnailInterface $thumbnail;

    public function __construct(
        string $name,
        FilesystemOperator $filesystem,
        CdnInterface $cdn,
        GeneratorInterface $generator,
        ValidatorInterface $validator,
        ThumbnailInterface $thumbnail,
        ?Constraints $constrains = null
    ) {
        parent::__construct(
            $name,
            $filesystem,
            $cdn,
            $generator,
            $validator,
            $constrains
        );
        $this->thumbnail = $thumbnail;
    }

    public function postPersist(MediaInterface $media): void
    {
        if (null === $media->getFile()) {
            return;
        }
        $this->copyTempFile($media);
        $this->generateThumbnails($media);
        $media->resetFile();
    }

    // Remove all generated thumbnails
    public function postRemove(MediaInterface $media): void
    {
        $hash = spl_object_hash($media);

        if (isset($this->clones[$hash])) {
            $media = $this->clones[$hash];
            unset($this->clones[$hash]);
        }
        $this->thumbnail->delete($this, $media);
        $this->deletePath($this->getFilesystemReference($media));
    }

    public function generateThumbnails(MediaInterface $media)
    {
        $this->thumbnail->generate($this, $media);
    }

    public function flushCdn(MediaInterface $media)
    {
//        if ($media->getId() && $this->requireThumbnails() && !$media->getCdnIsFlushable()) {
//            $flushPaths = [];
//            foreach ($this->getFormats() as $format => $settings) {
//                if (MediaProviderInterface::FORMAT_ADMIN === $format ||
//                    substr($format, 0, \strlen((string) $media->getContext())) === $media->getContext()) {
//                    $flushPaths[] = $this->getFilesystem()->get($this->generatePrivateUrl($media, $format), true)->getKey();
//                }
//            }
//            if (!empty($flushPaths)) {
//                $cdnFlushIdentifier = $this->getCdn()->flushPaths($flushPaths);
//                $media->setCdnFlushIdentifier($cdnFlushIdentifier);
//                $media->setCdnIsFlushable(true);
//                $media->setCdnStatus(CDNInterface::STATUS_TO_FLUSH);
//            }
//        }
    }
}
