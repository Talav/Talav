<?php

declare(strict_types=1);

namespace Talav\Component\Resource\Tests\Functional\Manager;

use PHPUnit\Framework\TestCase;
use Talav\Component\Resource\Factory\Factory;
use Talav\Component\Resource\Manager\ResourceManager;
use Talav\Component\Resource\Tests\Functional\Setup\Entity\TestEntity;
use Webfactory\Doctrine\ORMTestInfrastructure\ORMInfrastructure;

final class ResourceManagerTest extends TestCase
{
    /**
     * @var ORMInfrastructure
     */
    private $infrastructure;

    /**
     * @var ResourceManager
     */
    private $resourceManager;

    protected function setUp(): void
    {
        $this->infrastructure = ORMInfrastructure::createWithDependenciesFor(TestEntity::class);
        $this->resourceManager = new ResourceManager(
            TestEntity::class,
            $this->infrastructure->getEntityManager(),
            new Factory(TestEntity::class)
        );
    }

    /**
     * @test
     */
    public function it_returns_full_class_name()
    {
        self::assertEquals(
            "Talav\Component\Resource\Tests\Functional\Setup\Entity\TestEntity",
            $this->resourceManager->getClassName()
        );
    }

    /**
     * @test
     */
    public function it_creates_and_saves_and_updates_entity()
    {
        $entity = $this->resourceManager->create();
        $entity->name = 'test';
        self::assertInstanceOf(TestEntity::class, $entity);
        $this->resourceManager->add($entity);
        $this->resourceManager->flush();

        $entities = $this->resourceManager->getRepository()->findAll();
        self::assertEquals(1, count($entities));
        self::assertEquals('test', $entities[0]->name);

        $entity->name = 'blabla';
        $this->resourceManager->flush();
        self::assertEquals('blabla', $this->resourceManager->getRepository()->find($entity->id)->name);
    }
}
