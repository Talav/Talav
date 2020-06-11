<?php

declare(strict_types=1);

namespace Talav\MediaBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Talav\Component\Resource\Factory\Factory;
use Talav\Component\User\Canonicalizer\Canonicalizer;
use Talav\Component\User\Manager\UserManager;
use Talav\Component\User\Manager\UserOAuthManager;
use Talav\Component\User\Repository\UserRepository;
use Talav\Component\User\Security\PasswordUpdater;
use Talav\MediaBundle\Model\Media;
use Talav\UserBundle\Entity\User;
use Talav\UserBundle\Entity\UserOAuth;
use Talav\UserBundle\Mailer\UserMailer;

final class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('talav_media');
        $treeBuilder->getRootNode()
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
