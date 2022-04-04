<?php

declare(strict_types=1);

namespace Talav\MediaBundle\Tests\DependencyInjection\Extension;

use function PHPUnit\Framework\assertEquals;
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
        assertEquals(2, count($pool->getProviderList()));
    }
}
