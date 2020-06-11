<?php

declare(strict_types=1);

namespace Talav\MediaBundle\DependencyInjection;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Talav\Component\Resource\Factory\Factory;
use Talav\Component\User\Canonicalizer\CanonicalizerInterface;
use Talav\Component\User\Manager\UserManager;
use Talav\Component\User\Manager\UserManagerInterface;
use Talav\Component\User\Manager\UserOAuthManager;
use Talav\Component\User\Manager\UserOAuthManagerInterface;
use Talav\Component\User\Security\PasswordUpdaterInterface;
use Talav\ResourceBundle\DependencyInjection\Extension\AbstractResourceExtension;
use Talav\UserBundle\Mailer\UserMailer;
use Talav\UserBundle\Mailer\UserMailerInterface;

class TalavMediaExtension extends AbstractResourceExtension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        // Load services.
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');

        $this->registerResources("app", $config['resources'], $container);
    }
}
