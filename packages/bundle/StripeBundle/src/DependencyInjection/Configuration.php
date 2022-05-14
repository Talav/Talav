<?php

namespace Talav\StripeBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('talav_stripe');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->scalarNode('secret_key')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('webhook_secret')
                ->end()
            ->end()
        ;
        $this->addResourceSection($treeBuilder->getRootNode());

        return $treeBuilder;
    }

    private function addResourceSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('resources')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('product')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->arrayNode('classes')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('model')->cannotBeEmpty()->end()
                                        ->scalarNode('manager')->end()
                                        ->scalarNode('factory')->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('price')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->arrayNode('classes')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('model')->cannotBeEmpty()->end()
                                        ->scalarNode('manager')->end()
                                        ->scalarNode('factory')->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('mapping')
                    ->children()
                        ->scalarNode('product')->cannotBeEmpty()->end()
                        ->scalarNode('price')->cannotBeEmpty()->end()
                    ->end()
                ->end()
            ->end();
    }
}
