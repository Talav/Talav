<?php

declare(strict_types=1);

namespace Talav\Resource\Tests\Functional\Setup;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Talav\UserBundle\DataFixtures\UserFixtures;

trait Doctrine
{
    /**
     * @var ManagerRegistry
     */
    protected $doctrine;

    /**
     * @var EntityManagerInterface
     */
    protected $manager;

    /**
     * @before
     */
    protected function createSchema()
    {
        if ($metadata = $this->getMetadata()) {
            $schemaTool = new SchemaTool($this->manager);
            $schemaTool->dropSchema($metadata);
            $schemaTool->createSchema($metadata);
        }
    }

    /**
     * @before
     */
    protected function setUpDoctrine()
    {
        $this->doctrine = $this->createDoctrineRegistry();
        $this->manager = $this->doctrine->getManager();
    }

    /**
     * Returns all metadata by default.
     *
     * Override to only build selected metadata.
     * Return an empty array to prevent building the schema.
     *
     * @return array
     */
    protected function getMetadata(): array
    {
        return $this->manager->getMetadataFactory()->getAllMetadata();
    }

    /**
     * Override to build doctrine registry yourself.
     *
     * By default a Symfony container is used to create it. It requires the SymfonyKernel trait.
     *
     * @return ManagerRegistry
     */
    protected function createDoctrineRegistry(): ManagerRegistry
    {
        return self::$container->get('doctrine');
    }
}
