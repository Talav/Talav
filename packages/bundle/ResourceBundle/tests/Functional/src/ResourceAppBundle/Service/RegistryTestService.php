<?php

declare(strict_types=1);

namespace ResourceAppBundle\Service;

use Talav\Component\Registry\Registry\ServiceRegistryInterface;
use Talav\Component\Resource\Metadata\RegistryInterface;

final class RegistryTestService
{
    /**
     * @var RegistryInterface
     */
    public $registry;

    /**
     * @var ServiceRegistryInterface
     */
    public $serviceRegistry;

    public function __construct(
        RegistryInterface $registry,
        ServiceRegistryInterface $serviceRegistry
    ) {
        $this->registry = $registry;
        $this->serviceRegistry = $serviceRegistry;
    }

    /**
     * @return RegistryInterface
     */
    public function getRegistry(): RegistryInterface
    {
        return $this->registry;
    }

    /**
     * @return ServiceRegistryInterface
     */
    public function getServiceRegistry(): ServiceRegistryInterface
    {
        return $this->serviceRegistry;
    }
}