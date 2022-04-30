<?php

declare(strict_types=1);

namespace Talav\MediaBundle\Message\Dto;

use Talav\Component\Media\Model\MediaInterface;
use Talav\MediaBundle\Form\Model\MediaModel;

class ProcessMediaDto
{
    public ?string $name = null;

    public ?string $description = null;

    public ?MediaInterface $media = null;

    public ?MediaModel $formModel = null;

    public static function fromObjects(?MediaInterface $media, ?MediaModel $formModel): ProcessMediaDto
    {
        $dto = new ProcessMediaDto();
        $dto->media = $media;
        $dto->formModel = $formModel;

        return $dto;
    }
}
