<?php

declare(strict_types=1);

namespace Talav\Component\Media\Message\Dto\Media;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Talav\Component\Resource\Model\DomainEventInterface;

final class CreateMediaDto implements DomainEventInterface
{
    public ?string $context = null;

    public ?string $provider = null;

    public ?string $name = null;

    public ?string $description = null;

    public ?UploadedFile $file = null;
}
