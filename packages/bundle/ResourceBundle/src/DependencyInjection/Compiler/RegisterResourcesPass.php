<?php

declare(strict_types=1);

namespace Talav\ResourceBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Talav\Component\Resource\Model\ResourceInterface;

final class RegisterResourcesPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container): void
    {
        try {
            $resources = $container->getParameter('talav.resources');
            $registry = $container->findDefinition('talav.resource.registry');
        } catch (InvalidArgumentException $exception) {
            return;
        }
        foreach ($resources as $alias => $configuration) {
            $this->validateResource($configuration['classes']['model']);
            $registry->addMethodCall('addFromAliasAndConfiguration', [$alias, $configuration]);
        }
    }

    private function validateResource(string $class): void
    {
        if (!in_array(ResourceInterface::class, class_implements($class), true)) {
            throw new InvalidArgumentException(sprintf('Class "%s" must implement "%s" to be registered as a valid resource.', $class, ResourceInterface::class));
        }
    }
}
