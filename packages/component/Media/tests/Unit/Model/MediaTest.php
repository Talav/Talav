<?php

namespace Talav\Component\Media\Tests\Unit\Model;

use PHPUnit\Framework\TestCase;
use Talav\Component\Media\Model\Media;

class MediaTest extends TestCase
{
    /**
     * @test
     */
    public function it_calculates_provider_reference_without_extension()
    {
        $media = new Media();
        self::assertStringNotContainsString('.', $media->getProviderReference());
    }

    /**
     * @test
     */
    public function it_calculates_provider_reference_with_extension()
    {
        $media = new Media();
        $media->setFileExtension('png');
        self::assertStringContainsString('.png', $media->getProviderReference());
    }

    /**
     * @test
     */
    public function it_does_not_regenerate_reference_for_existing_reference()
    {
        $media = new Media();
        $ref = $media->getProviderReference();
        self::assertEquals($ref, $media->getProviderReference());
    }
}
