<?php

declare(strict_types=1);

namespace Talav\ResourceBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Doctrine\Common\Persistence\Mapping\ReflectionService;
use Doctrine\Common\Persistence\Mapping\RuntimeReflectionService;
use Talav\Component\Resource\Metadata\RegistryInterface;
use Talav\Component\Resource\Model\ResourceInterface;

abstract class AbstractDoctrineSubscriber implements EventSubscriber
{
    /** @var RegistryInterface */
    protected $resourceRegistry;

    /** @var RuntimeReflectionService|null */
    private $reflectionService;

    public function __construct(RegistryInterface $resourceRegistry)
    {
        $this->resourceRegistry = $resourceRegistry;
    }

    protected function isResource(ClassMetadata $metadata): bool
    {
        return $metadata->getReflectionClass()->implementsInterface(ResourceInterface::class);
    }

    protected function getReflectionService(): ReflectionService
    {
        if ($this->reflectionService === null) {
            $this->reflectionService = new RuntimeReflectionService();
        }

        return $this->reflectionService;
    }
}
