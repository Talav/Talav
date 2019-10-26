<?php

declare(strict_types=1);

namespace Talav\ResourceBundle\DependencyInjection\Compiler\Helper;

interface TargetEntitiesResolverInterface
{
    /**
     * @return array Interface to class map.
     */
    public function resolve(array $resourcesConfiguration): array;
}