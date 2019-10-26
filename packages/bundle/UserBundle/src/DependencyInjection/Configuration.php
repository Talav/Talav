<?php

declare(strict_types=1);

namespace Talav\UserBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Talav\Component\Resource\Factory\Factory;
use Talav\Component\User\Canonicalizer\Canonicalizer;
use Talav\Component\User\Manager\UserManager;
use Talav\Component\User\Manager\UserOAuthManager;
use Talav\Component\User\Repository\UserRepository;
use Talav\Component\User\Security\PasswordUpdater;
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
        $treeBuilder = new TreeBuilder('talav_user');
        $treeBuilder->getRootNode()
            ->children()
                ->arrayNode('resources')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('user')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->arrayNode('classes')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('model')->defaultValue(User::class)->end()
                                        ->scalarNode('manager')->defaultValue(UserManager::class)->end()
                                        ->scalarNode('repository')->defaultValue(UserRepository::class)->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('user_oauth')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->arrayNode('classes')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('model')->defaultValue(UserOAuth::class)->end()
                                        ->scalarNode('manager')->defaultValue(UserOAuthManager::class)->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('mailer')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('class')->defaultValue(UserMailer::class)->end()
                    ->end()
                ->end()
                ->arrayNode('canonicalizer')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('class')->defaultValue(Canonicalizer::class)->end()
                    ->end()
                ->end()
                ->arrayNode('password_updater')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('class')->defaultValue(PasswordUpdater::class)->end()
                    ->end()
                ->end()
                ->arrayNode('resetting')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('retry_ttl')->defaultValue('2 minutes')->end()
                        ->scalarNode('token_ttl')->defaultValue('24 hours')->end()
                    ->end()
                ->end()
                ->arrayNode('email')
                    ->isRequired()
                    ->children()
                        ->arrayNode('from')
                            ->children()
                                ->scalarNode('email')->isRequired()->cannotBeEmpty()->end()
                                ->scalarNode('name')->isRequired()->cannotBeEmpty()->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
