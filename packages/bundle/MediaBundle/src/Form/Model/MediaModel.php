<?php

declare(strict_types=1);

namespace Talav\MediaBundle\Form\Model;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class MediaModel
{
    public ?string $provider = null;

    public ?string $context = null;

    public ?UploadedFile $file = null;

    public bool $delete = false;

    public bool $unlink = false;
}
