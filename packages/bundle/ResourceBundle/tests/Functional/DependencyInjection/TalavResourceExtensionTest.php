<?php

declare(strict_types=1);

namespace Talav\ResourceBundle\Tests\DependencyInjection\Extension;

use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use ResourceAppBundle\Factory\AuthorFactoryInterface;
use ResourceAppBundle\Manager\AuthorManagerInterface;
use ResourceAppBundle\Repository\AuthorRepositoryInterface;
use ResourceAppBundle\Service\FirstAutowiredService;
use ResourceAppBundle\Service\RegistryTestService;
use ResourceAppBundle\Service\SecondAutowiredService;
use ResourceAppBundle\Service\ThirdAutowiredService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Talav\Component\Registry\Registry\ServiceRegistryInterface;
use Talav\Component\Resource\Factory\FactoryInterface;
use Talav\Component\Resource\Manager\ManagerInterface;
use Talav\Component\Resource\Metadata\RegistryInterface;
use Talav\Component\Resource\Repository\RepositoryInterface;

final class TalavResourceExtensionTest extends KernelTestCase
{
    public function setUp(): void
    {
        $databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();
        $databaseTool->loadFixtures();
    }

    /**
     * @test
     */
    public function it_correctly_auto_wires_default_values(): void
    {
        $service = static::getContainer()->get(FirstAutowiredService::class);
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
        $service = static::getContainer()->get(SecondAutowiredService::class);
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
        $service = static::getContainer()->get(ThirdAutowiredService::class);
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
        $registryTestService = static::getContainer()->get(RegistryTestService::class);
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
        $registryTestService = static::getContainer()->get(RegistryTestService::class);
        /** @var ServiceRegistryInterface $repoRegistry */
        $repoRegistry = $registryTestService->getServiceRegistry();
        $this->assertEquals(2, count($repoRegistry->all()));
    }

    /**
     * @test
     */
    public function it_registers_gedmo_timestampable_extension(): void
    {
        /** @var AuthorManagerInterface $manager */
        $manager = static::getContainer()->get('app.manager.author');
        $author = $manager->create();
        $author->setName('Test name');
        $manager->update($author, true);
        self::assertNotNull($author->getCreatedAt());
        self::assertNotNull($author->getUpdatedAt());
    }
}
