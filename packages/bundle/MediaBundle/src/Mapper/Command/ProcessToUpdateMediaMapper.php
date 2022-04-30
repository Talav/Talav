<?php

declare(strict_types=1);

namespace Talav\MediaBundle\Mapper\Command;

use AutoMapperPlus\CustomMapper\CustomMapper;
use Talav\Component\Media\Message\Dto\Media\UpdateMediaDto;
use Talav\MediaBundle\Message\Dto\ProcessMediaDto;
use Webmozart\Assert\Assert;

class ProcessToUpdateMediaMapper extends CustomMapper
{
    public function mapToObject($source, $destination)
    {
        /* @var ProcessMediaDto $source */
        Assert::isInstanceOf($source, ProcessMediaDto::class);
        /* @var UpdateMediaDto $destination */
        Assert::isInstanceOf($destination, UpdateMediaDto::class);

        $destination->file = $source->formModel->file;
        $destination->description = $source->description;
        $destination->name = $source->name;

        return $destination;
    }
}
