<?php

declare(strict_types=1);

namespace Talav\MediaBundle\Mapper\FormModel;

use AutoMapperPlus\CustomMapper\CustomMapper;
use Talav\Component\Media\Model\MediaInterface;
use Talav\MediaBundle\Form\Model\MediaModel;
use Webmozart\Assert\Assert;

class MediaToMediaModelMapper extends CustomMapper
{
    public function mapToObject($source, $destination)
    {
        Assert::isInstanceOf($source, MediaInterface::class);
        Assert::isInstanceOf($destination, MediaModel::class);

        $destination->context = $source->getContext();
        $destination->provider = $source->getProviderName();

        return $destination;
    }
}
