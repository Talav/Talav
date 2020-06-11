<?php

declare(strict_types=1);

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\RouteCollectionBuilder;

class AppKernel extends Kernel
{
    use MicroKernelTrait;

    /**
     * {@inheritdoc}
     */
    public function registerBundles()
    {
        return [
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Talav\ResourceBundle\TalavResourceBundle(),
            new Talav\UserBundle\TalavUserBundle(),
            new Http\HttplugBundle\HttplugBundle(),
            new HWI\Bundle\OAuthBundle\HWIOAuthBundle(),
            new AppBundle\AppBundle(),
            new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function configureContainer(ContainerBuilder $c, LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config.yml');
    }

    /**
     * {@inheritdoc}
     */
    protected function configureRoutes(RouteCollectionBuilder $routes)
    {
        $routes->import(__DIR__.'/config/routing.yml');
    }

    /**
     * {@inheritdoc}
     */
    public function getCacheDir()
    {
        return sys_get_temp_dir().'/TalavUserBundle/cache/'.$this->getEnvironment();
    }

    /**
     * {@inheritdoc}
     */
    public function getLogDir()
    {
        return sys_get_temp_dir().'/TalavUserBundle/logs';
    }
}
