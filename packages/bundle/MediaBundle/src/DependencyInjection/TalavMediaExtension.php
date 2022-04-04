<?php

declare(strict_types=1);

namespace Talav\MediaBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Talav\Component\Media\Context\ContextConfig;
use Talav\Component\Media\Provider\Constraints;
use Talav\ResourceBundle\DependencyInjection\Extension\AbstractResourceExtension;

class TalavMediaExtension extends AbstractResourceExtension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        // Load services.
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $this->registerResources('app', $config['resources'], $container);
        $this->configureCdn($container, $config['cdn']);
        $this->configureProviders($container, $config['providers']);
        $this->configureContexts($container, $config['contexts']);
    }

    public function configureCdn(ContainerBuilder $container, array $config): void
    {
        $container->getDefinition('talav.media.cdn.server')
            ->setArgument(0, $config['server']['path']);
    }

    public function configureProviders(ContainerBuilder $container, array $config): void
    {
        $container->getDefinition('talav.media.provider.file')
            ->setArgument(1, new Reference($config['file']['filesystem']))
            ->setArgument(2, new Reference($config['file']['cdn']))
            ->setArgument(3, new Reference($config['file']['generator']))
            ->setArgument(4, new Reference(ValidatorInterface::class))
            ->setArgument(5, new Definition(Constraints::class, [
                $config['file']['constraints']['extensions'],
                $config['file']['constraints']['file_constraints'],
                [],
            ]))
        ;
        $container->getDefinition('talav.media.provider.image')
            ->setArgument(1, new Reference($config['image']['filesystem']))
            ->setArgument(2, new Reference($config['image']['cdn']))
            ->setArgument(3, new Reference($config['image']['generator']))
            ->setArgument(4, new Reference(ValidatorInterface::class))
            ->setArgument(5, new Reference($config['image']['thumbnail']))
            ->setArgument(6, new Definition(Constraints::class, [
                $config['image']['constraints']['extensions'],
                $config['image']['constraints']['file_constraints'],
                $config['image']['constraints']['image_constraints'],
            ]))
        ;
    }

    public function configureContexts(ContainerBuilder $container, array $config): void
    {
        $pool = $container->getDefinition('talav.media.provider.pool');
        foreach ($config as $name => $conf) {
            $providers = [];
            foreach ($conf['providers'] as $provider) {
                $providers[] = new Reference($provider);
            }
            $pool->addMethodCall('addContext', [new Definition(ContextConfig::class, [
                $name,
                $providers,
                [],
            ])]);
        }
    }
}
