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
            new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),
            new Talav\ResourceBundle\TalavResourceBundle(),
            new Oneup\FlysystemBundle\OneupFlysystemBundle(),
            new Liip\ImagineBundle\LiipImagineBundle(),
            new Talav\MediaBundle\TalavMediaBundle(),
            new Vich\UploaderBundle\VichUploaderBundle(),
            new AppBundle\AppBundle(),
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
        $routes->import(__DIR__ . '/config/routing.xml');
    }

    /**
     * {@inheritdoc}
     */
    public function getCacheDir()
    {
        return sys_get_temp_dir().'/TalavMediaBundle/cache/'.$this->getEnvironment();
    }

    /**
     * {@inheritdoc}
     */
    public function getLogDir()
    {
        return sys_get_temp_dir().'/TalavMediaBundle/logs';
    }
}
