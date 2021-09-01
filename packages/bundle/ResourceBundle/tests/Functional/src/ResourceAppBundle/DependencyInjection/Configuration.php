<?php

declare(strict_types=1);

namespace ResourceAppBundle\DependencyInjection;

use ResourceAppBundle\Entity\Author;
use ResourceAppBundle\Entity\Book;
use ResourceAppBundle\Factory\AuthorFactory;
use ResourceAppBundle\Manager\AuthorManager;
use ResourceAppBundle\Repository\AuthorRepository;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('talav_resources');
        $treeBuilder->getRootNode()
            ->children()
                ->arrayNode('resources')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('book')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->arrayNode('classes')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('model')->defaultValue(Book::class)->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('author')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->arrayNode('classes')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('model')->defaultValue(Author::class)->end()
                                        ->scalarNode('factory')->defaultValue(AuthorFactory::class)->end()
                                        ->scalarNode('repository')->defaultValue(AuthorRepository::class)->end()
                                        ->scalarNode('manager')->defaultValue(AuthorManager::class)->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
