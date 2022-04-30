<?php

declare(strict_types=1);

namespace Talav\Component\Media\Mapper;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Talav\Component\Media\Model\MediaInterface;

trait FileToMediaMapperTrait
{
    public function mapFileToMedia(UploadedFile $source, MediaInterface $destination): void
    {
        $destination->setMimeType($source->getMimeType());
        $destination->setSize($source->getSize());
        $destination->setFileExtension($source->getExtension());
        $destination->setFileName($source->getClientOriginalName());

        // name is required, try to fill it
        if (is_null($destination->getName())) {
            $destination->setName($source->getClientOriginalName());
        }
    }
}
