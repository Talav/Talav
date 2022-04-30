<?php

namespace Talav\MediaBundle\Mapper\FormModel;

use PHPUnit\Framework\TestCase;
use Talav\MediaBundle\Entity\Media;
use Talav\MediaBundle\Form\Model\MediaModel;

class MediaToMediaModelMapperTest extends TestCase
{
    /**
     * @test
     */
    public function it_maps_context_and_provider_for_media()
    {
        $mapper = new MediaToMediaModelMapper();
        $media = new Media();
        $media->setProviderName('provider');
        $media->setContext('context');
        $model = $mapper->map($media, MediaModel::class);
        self::assertNotNull($model);
        self::assertEquals($media->getProviderName(), $model->provider);
        self::assertEquals($media->getContext(), $model->context);
    }
}
