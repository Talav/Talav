<?php

declare(strict_types=1);

namespace Talav\ResourceBundle\DependencyInjection\Extension;

use Doctrine\ORM\Mapping\ClassMetadata;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Reference;
use Talav\Component\Resource\Factory\Factory;
use Talav\Component\Resource\Metadata\Metadata;
use Talav\Component\Resource\Metadata\MetadataInterface;

abstract class AbstractResourceExtension extends Extension
{
    protected function registerResources(
        string $applicationName,
        array $resources,
        ContainerBuilder $container
    ): void {
        $defaults = $this->getDefaultParameters($container);
        foreach ($resources as $resourceName => $resourceConfig) {
            $alias = $applicationName . '.' . $resourceName;
            $resourceConfig['classes'] = array_merge($defaults, $resourceConfig['classes']);
            $resources = $container->hasParameter('talav.resources') ? $container->getParameter('talav.resources') : [];
            $resources = array_merge($resources, [$alias => $resourceConfig]);
            $container->setParameter('talav.resources', $resources);
            $metadata = Metadata::fromAliasAndConfiguration($alias, $resourceConfig);
            $this->loadServices($container, $metadata);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function loadServices(ContainerBuilder $container, MetadataInterface $metadata): void
    {
        $this->setClassesParameters($container, $metadata);
        $this->addFactory($container, $metadata);
        $this->addRepository($container, $metadata);
        $this->addManager($container, $metadata);
    }

    protected function setClassesParameters(ContainerBuilder $container, MetadataInterface $metadata): void
    {
        $container->setParameter($metadata->getServiceClass('model'), $metadata->getClass('model'));
        $container->setParameter($metadata->getServiceClass('factory'), $metadata->getClass('factory'));
        $container->setParameter($metadata->getServiceClass('repository'), $metadata->getClass('repository'));
        $container->setParameter($metadata->getServiceClass('manager'), $metadata->getClass('manager'));
    }

    /**
     * Adds a factory for entity
     */
    protected function addFactory(ContainerBuilder $container, MetadataInterface $metadata): void
    {
        $factoryClass = $metadata->getClass('factory');
        $definition = new Definition($factoryClass);
        $modelClass = $metadata->getClass('model');
        $definition->setAutowired(true);
        $definition->setPublic(true);
        $definition->setArgument(0, $modelClass);
        $container->setDefinition($metadata->getServiceId('factory'), $definition);

        foreach (class_implements($factoryClass) as $typehintClass) {
            $container->registerAliasForArgument(
                $metadata->getServiceId('factory'),
                $typehintClass,
                $metadata->getHumanizedName() . ' factory'
            );
        }
    }

    protected function addRepository(ContainerBuilder $container, MetadataInterface $metadata): void
    {
        $repositoryClass = $metadata->getClass('repository');
        $definition = new Definition($repositoryClass);
        $definition->setArgument(0, new Reference('doctrine.orm.entity_manager'));
        $definition->setArgument(1, $this->getClassMetadataDefinition($metadata));
        $definition->setPublic(true);
        $definition->setAutowired(true);
        $container->setDefinition($metadata->getServiceId('repository'), $definition);
        foreach (class_implements($repositoryClass) as $typehintClass) {
            $container->registerAliasForArgument(
                $metadata->getServiceId('repository'),
                $typehintClass,
                $metadata->getHumanizedName() . ' repository'
            );
        }
    }

    protected function addManager(ContainerBuilder $container, MetadataInterface $metadata): void
    {
        $managerClass = $metadata->getClass('manager');
        $definition = new Definition($managerClass);
        $modelClass = $metadata->getClass('model');
        $definition->setArgument(0, $modelClass);
        $definition->setArgument(1, new Reference('doctrine.orm.entity_manager'));
        $definition->setArgument(2, new Reference($metadata->getServiceId('factory')));
        $definition->setPublic(true);
        $definition->setAutowired(true);
        $container->setDefinition($metadata->getServiceId('manager'), $definition);
        foreach (class_implements($managerClass) as $typehintClass) {
            $container->registerAliasForArgument(
                $metadata->getServiceId('manager'),
                $typehintClass,
                $metadata->getHumanizedName() . ' manager'
            );
        }
    }

    protected function getClassMetadataDefinition(MetadataInterface $metadata): Definition
    {
        $definition = new Definition(ClassMetadata::class);
        $definition
            ->setFactory([new Reference('doctrine.orm.entity_manager'), 'getClassMetadata'])
            ->setArguments([$metadata->getClass('model')])
            ->setPublic(false)
        ;

        return $definition;
    }

    /**
     * Returns a list of default classes
     */
    protected function getDefaultParameters(ContainerBuilder $container): array
    {
        return [
            'factory' => $container->getParameter('talav.resource.default.factory.class'),
            'repository' => $container->getParameter('talav.resource.default.repository.class'),
            'manager' => $container->getParameter('talav.resource.default.manager.class'),
        ];
    }
}
