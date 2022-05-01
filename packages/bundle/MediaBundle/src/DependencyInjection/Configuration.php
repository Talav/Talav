<?php

declare(strict_types=1);

namespace Talav\MediaBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Talav\Component\Media\Manager\MediaManager;
use Talav\Component\Media\Model\Media;
use Talav\Component\Resource\Repository\ResourceRepository;

final class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('talav_media');
        $rootNode = $treeBuilder->getRootNode();
        $this->addResourcesSection($rootNode);
        $this->addProvidersSection($rootNode);
        $this->addCdnSection($rootNode);
        $this->addContextsSection($rootNode);

        return $treeBuilder;
    }

    private function addResourcesSection(ArrayNodeDefinition $node): void
    {
        $node
            ->children()
                ->arrayNode('resources')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('media')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->arrayNode('classes')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('model')->defaultValue(Media::class)->end()
                                        ->scalarNode('manager')->defaultValue(MediaManager::class)->end()
                                        ->scalarNode('repository')->defaultValue(ResourceRepository::class)->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }

    private function addCdnSection(ArrayNodeDefinition $node): void
    {
        $node
            ->children()
                ->arrayNode('cdn')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('server')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('path')->defaultValue('/media')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }

    private function addProvidersSection(ArrayNodeDefinition $node): void
    {
        $node
            ->children()
                ->arrayNode('providers')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('file')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('service')->defaultValue('talav.media.provider.file')->end()
                                ->scalarNode('filesystem')->defaultValue('oneup_flysystem.default_filesystem')->end()
                                ->scalarNode('generator')->defaultValue('talav.media.generator.uuid')->end()
                                ->scalarNode('cdn')->defaultValue('talav.media.cdn.server')->end()
                                ->arrayNode('constraints')
                                ->addDefaultsIfNotSet()
                                    ->children()
                                        ->arrayNode('extensions')
                                            ->prototype('scalar')->end()
                                            ->defaultValue([
                                                'pdf', 'txt', 'rtf',
                                                'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx',
                                                'odt', 'odg', 'odp', 'ods', 'odc', 'odf', 'odb',
                                                'csv',
                                                'xml',
                                            ])
                                        ->end()
                                        ->variableNode('file_constraints')
                                        ->defaultValue([
                                            'mimeTypes' => ['application/pdf', 'application/x-pdf', 'application/rtf', 'text/html', 'text/rtf', 'text/plain',
                                                'application/excel', 'application/msword', 'application/vnd.ms-excel', 'application/vnd.ms-powerpoint',
                                                'application/vnd.ms-powerpoint', 'application/vnd.oasis.opendocument.text', 'application/vnd.oasis.opendocument.graphics', 'application/vnd.oasis.opendocument.presentation', 'application/vnd.oasis.opendocument.spreadsheet', 'application/vnd.oasis.opendocument.chart', 'application/vnd.oasis.opendocument.formula', 'application/vnd.oasis.opendocument.database', 'application/vnd.oasis.opendocument.image',
                                                'text/comma-separated-values',
                                                'text/xml', 'application/xml',
                                                'application/zip', ],
                                            'maxSize' => '5M',
                                        ])
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()

                        ->arrayNode('image')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('service')->defaultValue('talav.media.provider.image')->end()
                                ->scalarNode('filesystem')->defaultValue('oneup_flysystem.default_filesystem')->end()
                                ->scalarNode('generator')->defaultValue('talav.media.generator.uuid')->end()
                                ->scalarNode('cdn')->defaultValue('talav.media.cdn.server')->end()
                                ->scalarNode('thumbnail')->defaultValue('talav.media.thumbnail.glide')->end()
                                ->arrayNode('constraints')
                                ->addDefaultsIfNotSet()
                                    ->children()
                                        ->arrayNode('extensions')
                                            ->prototype('scalar')->end()
                                            ->defaultValue(['jpg', 'png', 'jpeg'])
                                        ->end()
                                        ->variableNode('file_constraints')
                                        ->defaultValue([
                                            'mimeTypes' => [
                                                'image/pjpeg',
                                                'image/jpeg',
                                                'image/png',
                                                'image/x-png',
                                            ],
                                            'maxSize' => '5M',
                                        ])
                                        ->end()
                                        ->variableNode('image_constraints')
                                        ->defaultValue([
                                            'minWidth' => 100,
                                            'minHeight' => 100,
                                            'maxWidth' => 3000,
                                            'maxHeight' => 3000,
                                        ])
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addContextsSection(ArrayNodeDefinition $node): void
    {
        $node
            ->children()
                ->arrayNode('contexts')
                    ->useAttributeAsKey('id')
                    ->prototype('array')
                        ->children()
                            ->arrayNode('providers')
                                ->prototype('scalar')->end()
                            ->end()
                            ->arrayNode('formats')
                                ->useAttributeAsKey('name')
                                ->prototype('array')
                                    ->children()
                                        ->scalarNode('or')->defaultNull()->end()
                                        ->scalarNode('crop')->defaultNull()->end()
                                        ->integerNode('w')->defaultNull()->end()
                                        ->integerNode('h')->defaultNull()->end()
                                        ->scalarNode('fit')->defaultNull()->end()
                                        ->integerNode('dpr')->defaultNull()->end()
                                        ->integerNode('bri')->defaultNull()->end()
                                        ->integerNode('con')->defaultNull()->end()
                                        ->floatNode('gam')->defaultNull()->end()
                                        ->integerNode('sharp')->defaultNull()->end()
                                        ->integerNode('blur')->defaultNull()->end()
                                        ->integerNode('pixel')->defaultNull()->end()
                                        ->scalarNode('filt')->defaultNull()->end()
                                        ->scalarNode('mark')->defaultNull()->end()
                                        ->scalarNode('markw')->defaultNull()->end()
                                        ->scalarNode('markh')->defaultNull()->end()
                                        ->scalarNode('markx')->defaultNull()->end()
                                        ->scalarNode('marky')->defaultNull()->end()
                                        ->scalarNode('markpad')->defaultNull()->end()
                                        ->scalarNode('markpos')->defaultNull()->end()
                                        ->scalarNode('markalpha')->defaultNull()->end()
                                        ->scalarNode('bg')->defaultNull()->end()
                                        ->scalarNode('border')->defaultNull()->end()
                                        ->integerNode('q')->defaultNull()->end()
                                        ->scalarNode('fm')->defaultNull()->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }
}
