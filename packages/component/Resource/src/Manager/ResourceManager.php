<?php

declare(strict_types=1);

namespace Talav\Component\Resource\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Talav\Component\Resource\Factory\FactoryInterface;
use Talav\Component\Resource\Model\ResourceInterface;
use Talav\Component\Resource\Repository\RepositoryInterface;

class ResourceManager implements ManagerInterface
{
    protected string $className;

    protected EntityManagerInterface $em;

    protected FactoryInterface $factory;

    public function __construct($className, EntityManagerInterface $em, FactoryInterface $factory)
    {
        $this->className = $className;
        $this->em = $em;
        $this->factory = $factory;
    }

    /**
     * {@inheritdoc}
     */
    public function getClassName(): string
    {
        if (false !== strpos($this->className, ':')) {
            $metadata = $this->em->getClassMetadata($this->className);
            $this->className = $metadata->getName();
        }

        return $this->className;
    }

    /**
     * {@inheritdoc}
     */
    public function create(): ResourceInterface
    {
        return $this->factory->create();
    }

    /**
     * {@inheritdoc}
     */
    public function add(ResourceInterface $resource): void
    {
        $this->em->persist($resource);
    }

    /**
     * {@inheritdoc}
     */
    public function remove(ResourceInterface $resource): void
    {
        if (null !== $this->getRepository()->find($resource->getId())) {
            $this->em->remove($resource);
            $this->em->flush();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getRepository(): RepositoryInterface
    {
        return $this->em->getRepository($this->className);
    }

    /**
     * {@inheritdoc}
     */
    public function getFactory(): FactoryInterface
    {
        return $this->factory;
    }

    /**
     * {@inheritdoc}
     */
    public function update(ResourceInterface $resource, bool $flush = false): void
    {
        $this->add($resource);
        if ($flush) {
            $this->em->flush();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function reload(ResourceInterface $resource): ResourceInterface
    {
        $this->em->refresh($resource);

        return $resource;
    }

    /**
     * {@inheritdoc}
     */
    public function flush(): void
    {
        $this->em->flush();
    }
}
