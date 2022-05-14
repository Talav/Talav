<?php

declare(strict_types=1);

namespace Talav\ResourceBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Talav\Component\Resource\Metadata\Metadata;

final class RegisterResourceManagerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasParameter('talav.resources') || !$container->has('talav.registry.manager')) {
            return;
        }
        $resources = $container->getParameter('talav.resources');
        $registry = $container->findDefinition('talav.registry.manager');
        foreach ($resources as $alias => $configuration) {
            $metadata = Metadata::fromAliasAndConfiguration($alias, $configuration);
            $registry->addMethodCall('register', [$metadata->getClass('model'), new Reference($metadata->getServiceId('manager'))]);
        }
    }
}
