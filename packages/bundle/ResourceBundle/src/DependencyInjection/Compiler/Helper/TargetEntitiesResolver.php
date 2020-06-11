<?php

declare(strict_types=1);

namespace Talav\ResourceBundle\DependencyInjection\Compiler\Helper;

final class TargetEntitiesResolver implements TargetEntitiesResolverInterface
{
    /**
     * {@inheritdoc}
     */
    public function resolve(array $resources): array
    {
        $interfaces = [];
        foreach ($resources as $alias => $configuration) {
            $model = $this->getModel($alias, $configuration);
            foreach (class_implements($model) as $interface) {
                $interfaces[$interface][] = $model;
            }
        }
        $interfaces = array_filter($interfaces, function (array $classes): bool {
            return count($classes) === 1;
        });
        $interfaces = array_map(function (array $classes): string {
            return (string) current($classes);
        }, $interfaces);

        return $interfaces;
    }

    private function getModel(string $alias, array $configuration): string
    {
        if (!isset($configuration['classes']['model'])) {
            throw new \InvalidArgumentException(sprintf('Could not get model class from resource "%s".', $alias));
        }

        return $configuration['classes']['model'];
    }
}
