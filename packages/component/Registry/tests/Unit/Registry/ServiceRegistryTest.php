<?php

declare(strict_types=1);

namespace Talav\Component\Registry\Tests\Unit\Registry;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Talav\Component\Registry\Exception\ExistingServiceException;
use Talav\Component\Registry\Registry\ServiceRegistry;
use Talav\Component\Registry\Tests\Unit\Registry\Fixtures\TestInterface;

final class ServiceRegistryTest extends TestCase
{
    /**
     * @test
     */
    public function it_correctly_registers_2_services(): void
    {
        $mock1 = $this->getMockBuilder(TestInterface::class)->getMock();
        $mock2 = $this->getMockBuilder(TestInterface::class)->getMock();
        $registry = new ServiceRegistry(TestInterface::class);
        $registry->register('mock1', $mock1);
        $registry->register('mock2', $mock2);
        $this->assertEquals(2, count($registry->all()));
    }

    /**
     * @test
     */
    public function it_does_not_allow_to_register_the_same_key_twice(): void
    {
        $this->expectException(ExistingServiceException::class);
        $mock1 = $this->getMockBuilder(TestInterface::class)->getMock();
        $mock2 = $this->getMockBuilder(TestInterface::class)->getMock();
        $registry = new ServiceRegistry(TestInterface::class);
        $registry->register('mock1', $mock1);
        $registry->register('mock1', $mock2);
    }

    /**
     * @test
     */
    public function it_does_not_allow_to_register_different_interfaces(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $mock1 = $this->getMockBuilder(TestInterface::class)->getMock();
        $mock2 = $this->getMockBuilder(IncorrectInterface::class)->getMock();
        $registry = new ServiceRegistry(TestInterface::class);
        $registry->register('mock1', $mock1);
        $registry->register('mock2', $mock2);
    }

    /**
     * @test
     */
    public function it_does_not_allow_to_register_non_objects(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $registry = new ServiceRegistry(TestInterface::class);
        $registry->register('mock1', null);
    }
}
