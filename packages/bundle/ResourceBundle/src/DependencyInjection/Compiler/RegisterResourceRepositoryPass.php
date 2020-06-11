<?php

declare(strict_types=1);

namespace Talav\ResourceBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class RegisterResourceRepositoryPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasParameter('talav.resources') || !$container->has('talav.registry.repository')) {
            return;
        }
        $resources = $container->getParameter('talav.resources');
        $repositoryRegistry = $container->findDefinition('talav.registry.repository');
        foreach ($resources as $alias => $configuration) {
            [$applicationName, $resourceName] = explode('.', $alias, 2);
            $repositoryId = sprintf('%s.repository.%s', $applicationName, $resourceName);
            if ($container->has($repositoryId)) {
                $repositoryRegistry->addMethodCall('register', [$alias, new Reference($repositoryId)]);
            }
        }
    }
}
