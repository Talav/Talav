<?php

declare(strict_types=1);

namespace Talav\MediaBundle\Tests\DependencyInjection\Extension;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Talav\Component\Media\Provider\ProviderPool;

final class TalavMediaExtensionTest extends KernelTestCase
{
    /**
     * @test
     */
    public function it_correctly_registers_providers(): void
    {
        self::assertTrue(static::getContainer()->has('talav.media.provider.file'));
        self::assertTrue(static::getContainer()->has('talav.media.provider.image'));
    }

    /**
     * @test
     */
    public function it_adds_all_contexts_to_provider_pool(): void
    {
        /** @var ProviderPool $pool */
        $pool = static::getContainer()->get('talav.media.provider.pool');
        self::assertCount(2, $pool->getProviderList());
    }

    /**
     * @test
     */
    public function it_adds_all_formats_to_providers(): void
    {
        /** @var ProviderPool $pool */
        $pool = static::getContainer()->get('talav.media.provider.pool');
        self::assertCount(0, $pool->getProvider('file')->getFormats());
        self::assertCount(3, $pool->getProvider('image')->getFormats());
    }

    /**
     * @test
     */
    public function it_sets_template_config(): void
    {
        /** @var ProviderPool $pool */
        $pool = static::getContainer()->get('talav.media.provider.pool');
        self::assertNotNull($pool->getProvider('file')->getTemplateConfig());
        self::assertNotNull($pool->getProvider('image')->getTemplateConfig());
    }
}
