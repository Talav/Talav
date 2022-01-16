<?php

declare(strict_types=1);

namespace Talav\Component\Media\Provider;

use League\Flysystem\FilesystemOperator;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
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

    public function generateThumbnails(MediaInterface $media)
    {
        $this->thumbnail->generate($this, $media);
    }

//    /**
//     * {@inheritdoc}
//     */
//    public function transform(MediaInterface $media): void
//    {
//        if (null === $media->getBinaryContent()) {
//            return;
//        }
//
//        $this->doTransform($media);
//        $this->flushCdn($media);
//    }

    /**
     * {@inheritdoc}
     */
    protected function doTransform(MediaInterface $media): void
    {
//        // this is the name used to store the file
//        if (!$media->getProviderReference() ||
//            MediaInterface::MISSING_BINARY_REFERENCE === $media->getProviderReference()
//        ) {
//            $media->setProviderReference($this->generateReferenceName($media));
//        }
//
//        if ($media->getBinaryContent() instanceof File) {
//            $media->setContentType($media->getBinaryContent()->getMimeType());
//            $media->setSize($media->getBinaryContent()->getSize());
//        }
//
//        $media->setProviderStatus(MediaInterface::STATUS_OK);
//
//        // from image provider
//        if ($media->getBinaryContent() instanceof UploadedFile) {
//            $fileName = $media->getBinaryContent()->getClientOriginalName();
//        } elseif ($media->getBinaryContent() instanceof File) {
//            $fileName = $media->getBinaryContent()->getFilename();
//        } else {
//            // Should not happen, FileProvider should throw an exception in that case
//            return;
//        }
//
//        if (!in_array(strtolower(pathinfo($fileName, PATHINFO_EXTENSION)), $this->allowedExtensions, true)
//            || !in_array($media->getBinaryContent()->getMimeType(), $this->allowedMimeTypes, true)) {
//            return;
//        }
//
//        list($width, $height) = getimagesize($media->getBinaryContent()->getPathname());
//        $media->setWidth($width);
//        $media->setHeight($height);
//
//        $media->setProviderStatus(MediaInterface::STATUS_OK);
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
