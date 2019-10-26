<?php

declare(strict_types=1);

namespace Talav\ResourceBundle\Tests\DependencyInjection\Extension;

use AppBundle\Entity\AbstractBook;
use AppBundle\Factory\AuthorFactoryInterface;
use AppBundle\Manager\AuthorManagerInterface;
use AppBundle\Repository\AuthorRepositoryInterface;
use AppBundle\Service\FirstAutowiredService;
use AppBundle\Service\RegistryTestService;
use AppBundle\Service\SecondAutowiredService;
use AppBundle\Service\ThirdAutowiredService;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Talav\Component\Registry\Registry\ServiceRegistryInterface;
use Talav\Component\Resource\Factory\Factory;
use Talav\Component\Resource\Factory\FactoryInterface;
use Talav\Component\Resource\Manager\ManagerInterface;
use Talav\Component\Resource\Metadata\RegistryInterface;
use Talav\Component\Resource\Repository\RepositoryInterface;
use Talav\Component\Resource\Repository\ResourceRepository;
use Talav\ResourceBundle\Tests\Unit\DependencyInjection\Extension\TestExtension;
use Talav\ResourceBundle\Tests\Functional\Setup\Doctrine;
use Talav\ResourceBundle\Tests\Functional\Setup\SymfonyKernel;

final class TalavResourceExtensionTest extends KernelTestCase
{
    use SymfonyKernel;
    use Doctrine;

    /**
     * @test
     */
    public function it_correctly_auto_wires_default_values(): void
    {
        $service = self::$kernel->getContainer()->get(FirstAutowiredService::class);
        $this->assertInstanceOf(FirstAutowiredService::class, $service);
        $this->assertInstanceOf(FactoryInterface::class, $service->getBookFactory());
        $this->assertInstanceOf(RepositoryInterface::class, $service->getBookRepository());
        $this->assertInstanceOf(ManagerInterface::class, $service->getBookManager());

    }

    /**
     * @test
     */
    public function it_correctly_auto_wires_custom_interfaces(): void
    {
        $service = self::$kernel->getContainer()->get(SecondAutowiredService::class);
        $this->assertInstanceOf(SecondAutowiredService::class, $service);
        $this->assertInstanceOf(AuthorFactoryInterface::class, $service->getAuthorFactory());
        $this->assertInstanceOf(AuthorRepositoryInterface::class, $service->getAuthorRepository());
        $this->assertInstanceOf(AuthorManagerInterface::class, $service->getAuthorManager());
    }

    /**
     * @test
     */
    public function it_correctly_auto_wires_default_interfaces_with_custom_services(): void
    {
        $service = self::$kernel->getContainer()->get(ThirdAutowiredService::class);
        $this->assertInstanceOf(ThirdAutowiredService::class, $service);
        $this->assertInstanceOf(AuthorFactoryInterface::class, $service->getAuthorFactory());
        $this->assertInstanceOf(AuthorRepositoryInterface::class, $service->getAuthorRepository());
        $this->assertInstanceOf(AuthorManagerInterface::class, $service->getAuthorManager());
    }

    /**
     * @test
     */
    public function it_registers_all_resources_in_registry(): void
    {
        /** @var RegistryTestService $registry */
        $registryTestService = self::$kernel->getContainer()->get(RegistryTestService::class);
        $registry = $registryTestService->getRegistry();
        $this->assertInstanceOf(RegistryInterface::class, $registry);
        $this->assertEquals(2, count($registry->getAll()));
    }

    /**
     * @test
     */
    public function it_registers_all_repositories_in_registry(): void
    {
        /** @var RegistryTestService $registry */
        $registryTestService = self::$kernel->getContainer()->get(RegistryTestService::class);
        /** @var ServiceRegistryInterface $repoRegistry */
        $repoRegistry = $registryTestService->getServiceRegistry();
        $this->assertEquals(2, count($repoRegistry->all()));
    }
}
