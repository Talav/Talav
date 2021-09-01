<?php

declare(strict_types=1);

namespace Talav\Component\Resource\Tests\Functional\Repository;

use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;
use Talav\Component\Resource\Repository\RepositoryInterface;
use Talav\Component\Resource\Repository\ResourceRepository;
use Talav\Component\Resource\Tests\Functional\Setup\Entity\TestEntity;
use Webfactory\Doctrine\ORMTestInfrastructure\ORMInfrastructure;

final class ResourceRepositoryTest extends TestCase
{
    /**
     * @var ORMInfrastructure
     */
    private $infrastructure;

    /**
     * @var ResourceRepository
     */
    private $resourceRepository;

    /**
     * @var EntityManager
     */
    private $em;

    protected function setUp(): void
    {
        $this->infrastructure = ORMInfrastructure::createWithDependenciesFor(TestEntity::class);
        $this->resourceRepository = $this->infrastructure->getRepository(TestEntity::class);
        $this->em = $this->infrastructure->getEntityManager();
        $this->insertRecords();
    }

    /**
     * @test
     */
    public function it_creates_paginator()
    {
        $paginator = $this->resourceRepository->createPaginator();
        self::assertEquals(10, $paginator->count());
    }

    /**
     * @test
     */
    public function it_creates_paginator_with_filtering_by_value()
    {
        $paginator = $this->resourceRepository->createPaginator(['title' => 'Title 1']);
        self::assertEquals(5, $paginator->count());
    }

    /**
     * @test
     */
    public function it_creates_paginator_with_filtering_by_array()
    {
        $paginator = $this->resourceRepository->createPaginator(['title' => ['Title 1', 'Title 2']]);
        self::assertEquals(10, $paginator->count());
    }

    /**
     * @test
     */
    public function it_creates_paginator_with_sorting()
    {
        $paginator = $this->resourceRepository->createPaginator([], ['name' => RepositoryInterface::ORDER_DESCENDING]);
        $paginator->setMaxPerPage(2);
        $entities = $paginator->getCurrentPageResults();
        self::assertEquals(2, count($entities));
        self::assertEquals('Name 9', $entities[0]->name);
    }

    private function insertRecords()
    {
        for ($i = 1; $i <= 5; ++$i) {
            $entity = new TestEntity();
            $entity->name = 'Name '.$i;
            $entity->title = 'Title 1';
            $this->em->persist($entity);
        }
        for ($i = 6; $i <= 10; ++$i) {
            $entity = new TestEntity();
            $entity->name = 'Name '.$i;
            $entity->title = 'Title 2';
            $this->em->persist($entity);
        }
        $this->em->flush();
    }
}
