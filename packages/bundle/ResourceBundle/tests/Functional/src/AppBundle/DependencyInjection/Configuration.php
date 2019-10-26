<?php

declare(strict_types=1);

namespace AppBundle\DependencyInjection;

use AppBundle\Entity\AbstractAuthor;
use AppBundle\Entity\AbstractBook;
use AppBundle\Entity\Author;
use AppBundle\Entity\AuthorInterface;
use AppBundle\Entity\Book;
use AppBundle\Entity\BookInterface;
use AppBundle\Factory\AuthorFactory;
use AppBundle\Factory\AuthorFactoryInterface;
use AppBundle\Manager\AuthorManager;
use AppBundle\Manager\AuthorManagerInterface;
use AppBundle\Repository\AuthorRepository;
use AppBundle\Repository\AuthorRepositoryInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Talav\Component\Resource\Factory\Factory;
use Talav\Component\Resource\Manager\ResourceManager;
use Talav\Component\Resource\Repository\ResourceRepository;

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
