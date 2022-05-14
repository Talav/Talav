<?php

namespace Talav\StripeBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Talav\Component\Resource\Metadata\Metadata;
use Talav\ResourceBundle\DependencyInjection\Extension\AbstractResourceExtension;

class TalavStripeExtension extends AbstractResourceExtension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        // Load our services.
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config')
        );
        $loader->load('services.yml');

        $container->setParameter(
            'talav_stripe.secret_key',
            $config['secret_key']
        );
        $container->setParameter(
            'talav_stripe.webhook_secret',
            $config['webhook_secret']
        );
        $container->setParameter(
            'talav_stripe.class_map',
            $config['mapping']
        );

        $this->registerResources('app', $config['resources'], $container);

        $tempManagerMap = [];
        $defaults = $this->getDefaultParameters($container);
        foreach ($config['resources'] as $resourceName => $resourceConfig) {
            $alias = 'app.'.$resourceName;
            $resourceConfig['classes'] = array_merge($defaults, $resourceConfig['classes']);
            $metadata = Metadata::fromAliasAndConfiguration($alias, $resourceConfig);
            $tempManagerMap[$resourceConfig['classes']['model']] = $container->findDefinition($metadata->getServiceId('manager'));
        }
    }
}
