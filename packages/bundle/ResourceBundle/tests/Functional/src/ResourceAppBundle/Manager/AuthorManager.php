<?php

declare(strict_types=1);

namespace ResourceAppBundle\Manager;

use Doctrine\ORM\EntityManagerInterface;
use ResourceAppBundle\Service\RegistryTestService;
use Talav\Component\Resource\Factory\FactoryInterface;
use Talav\Component\Resource\Manager\ResourceManager;

final class AuthorManager extends ResourceManager implements AuthorManagerInterface
{
    /**
     * @var RegistryTestService
     */
    protected $registryTestService;

    public function __construct(
        $className,
        EntityManagerInterface $em,
        FactoryInterface $factory,
        RegistryTestService $registryTestService
    ) {
        $this->className = $className;
        $this->em = $em;
        $this->factory = $factory;
        $this->registryTestService = $registryTestService;
    }
}
