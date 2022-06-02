<?php

declare(strict_types=1);

namespace Talav\UserBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Talav\Component\User\Canonicalizer\CanonicalizerInterface;
use Talav\Component\User\Security\PasswordUpdaterInterface;
use Talav\ResourceBundle\DependencyInjection\Extension\AbstractResourceExtension;
use Talav\UserBundle\EventSubscriber\WelcomeEmailSubscriber;
use Talav\UserBundle\Mailer\UserMailer;
use Talav\UserBundle\Mailer\UserMailerInterface;

class TalavUserExtension extends AbstractResourceExtension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        // Load services.
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $container->autowire(UserMailerInterface::class, $config['mailer']['class']);
        $container->autowire(CanonicalizerInterface::class, $config['canonicalizer']['class']);
        $container->autowire(PasswordUpdaterInterface::class, $config['password_updater']['class']);

        if (UserMailer::class == $config['mailer']['class']) {
            $definition = $container->getDefinition(UserMailerInterface::class);
            $definition->setArgument(3, [
                'email' => $config['email']['from']['email'],
                'name' => $config['email']['from']['name'],
            ]);
        }

        // WelcomeEmailSubscriber is registered by default, remove it if config does not require confirmation email
        if (!$config['registration']['email']) {
            $container->removeDefinition(WelcomeEmailSubscriber::class);
        }

        $container->setParameter('talav_user.resetting.retry_ttl', $config['resetting']['retry_ttl']);
        $container->setParameter('talav_user.resetting.token_ttl', $config['resetting']['token_ttl']);
        $container->setParameter('talav_user.registration.form_type', $config['registration']['form']['type']);
        $container->setParameter('talav_user.registration.form_model', $config['registration']['form']['model']);
        $container->setParameter(
            'talav_user.registration.form_validation_groups',
            $config['registration']['form']['validation_groups']
        );

        $this->registerResources('app', $config['resources'], $container);
    }
}
