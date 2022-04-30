<?php

declare(strict_types=1);

namespace Talav\MediaBundle\Mapper\Command;

use AutoMapperPlus\CustomMapper\CustomMapper;
use Talav\Component\Media\Message\Dto\Media\CreateMediaDto;
use Talav\MediaBundle\Message\Dto\ProcessMediaDto;
use Webmozart\Assert\Assert;

class ProcessToCreateMediaMapper extends CustomMapper
{
    public function mapToObject($source, $destination)
    {
        /* @var ProcessMediaDto $source */
        Assert::isInstanceOf($source, ProcessMediaDto::class);
        /* @var CreateMediaDto $destination */
        Assert::isInstanceOf($destination, CreateMediaDto::class);

        $destination->provider = $source->formModel->provider;
        $destination->context = $source->formModel->context;
        $destination->file = $source->formModel->file;
        $destination->description = $source->description;
        $destination->name = $source->name;

        return $destination;
    }
}
