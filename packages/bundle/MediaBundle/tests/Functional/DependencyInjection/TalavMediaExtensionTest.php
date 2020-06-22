<?php

declare(strict_types=1);

namespace Talav\MediaBundle\Tests\DependencyInjection\Extension;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Talav\Component\Media\Provider\ProviderPool;
use Talav\MediaBundle\Tests\Functional\Setup\Doctrine;
use Talav\MediaBundle\Tests\Functional\Setup\SymfonyKernel;
use function PHPUnit\Framework\assertEquals;

final class TalavMediaExtensionTest extends KernelTestCase
{
    use SymfonyKernel;
    use Doctrine;

    /**
     * @test
     */
    public function it_correctly_registers_providers(): void
    {
        self::assertTrue(self::$kernel->getContainer()->has('talav.media.provider.file'));
        self::assertTrue(self::$kernel->getContainer()->has('talav.media.provider.image'));
    }

    /**
     * @test
     */
    public function it_adds_all_contexts_to_provider_pool(): void
    {
        /** @var ProviderPool $pool */
        $pool = self::$kernel->getContainer()->get('talav.media.provider.pool');
        assertEquals(1, count($pool->getProviderList()));
    }
}