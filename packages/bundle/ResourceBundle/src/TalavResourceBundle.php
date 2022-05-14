<?php

declare(strict_types=1);

namespace Talav\ResourceBundle;

use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Talav\ResourceBundle\DependencyInjection\Compiler\DoctrineTargetEntitiesResolverPass;
use Talav\ResourceBundle\DependencyInjection\Compiler\Helper\TargetEntitiesResolver;
use Talav\ResourceBundle\DependencyInjection\Compiler\RegisterResourceManagerPass;
use Talav\ResourceBundle\DependencyInjection\Compiler\RegisterResourceRepositoryPass;
use Talav\ResourceBundle\DependencyInjection\Compiler\RegisterResourcesPass;

class TalavResourceBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);
        $container->addCompilerPass(new RegisterResourcesPass());
        $container->addCompilerPass(
            new DoctrineTargetEntitiesResolverPass(new TargetEntitiesResolver()),
            PassConfig::TYPE_BEFORE_OPTIMIZATION,
            1
        );
        $container->addCompilerPass(new RegisterResourceRepositoryPass());
        $container->addCompilerPass(new RegisterResourceManagerPass());
    }
}
