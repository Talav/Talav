<?php

declare(strict_types=1);

namespace MediaAppBundle\Form\Model;

use Talav\MediaBundle\Form\Model\MediaModel;

class AuthorModel
{
    public string $name;

    public ?MediaModel $media;
}
