<?php

declare(strict_types=1);

namespace Talav\ResourceBundle\DependencyInjection\Compiler;

use Doctrine\Common\EventSubscriber;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Talav\ResourceBundle\DependencyInjection\Compiler\Helper\TargetEntitiesResolverInterface;

/**
 * Resolves given target entities with container parameters.
 */
final class DoctrineTargetEntitiesResolverPass implements CompilerPassInterface
{
    /** @var TargetEntitiesResolverInterface */
    private $targetEntitiesResolver;

    public function __construct(TargetEntitiesResolverInterface $targetEntitiesResolver)
    {
        $this->targetEntitiesResolver = $targetEntitiesResolver;
    }

    public function process(ContainerBuilder $container): void
    {
        try {
            $resources = $container->getParameter('talav.resources');
            $resolveTargetEntityListener = $container->findDefinition('doctrine.orm.listeners.resolve_target_entity');
        } catch (InvalidArgumentException $exception) {
            return;
        }
        $interfaces = $this->targetEntitiesResolver->resolve($resources);
        foreach ($interfaces as $interface => $model) {
            $resolveTargetEntityListener->addMethodCall('addResolveTargetEntity', [$interface, $model, []]);
        }
        $resolveTargetEntityListenerClass = $container->getParameterBag()->resolveValue($resolveTargetEntityListener->getClass());
        if (is_a($resolveTargetEntityListenerClass, EventSubscriber::class, true)) {
            if (!$resolveTargetEntityListener->hasTag('doctrine.event_subscriber')) {
                $resolveTargetEntityListener->addTag('doctrine.event_subscriber');
            }
        } elseif (!$resolveTargetEntityListener->hasTag('doctrine.event_listener')) {
            $resolveTargetEntityListener->addTag('doctrine.event_listener', ['event' => 'loadClassMetadata']);
        }
    }
}
