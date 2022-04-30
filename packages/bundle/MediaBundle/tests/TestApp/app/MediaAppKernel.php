<?php

declare(strict_types=1);

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

class MediaAppKernel extends Kernel
{
    use MicroKernelTrait;

    public function registerBundles(): iterable
    {
        return [
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),
            new Talav\ResourceBundle\TalavResourceBundle(),
            new Oneup\FlysystemBundle\OneupFlysystemBundle(),
            new Talav\MediaBundle\TalavMediaBundle(),
            new MediaAppBundle\MediaAppBundle(),
            new Liip\TestFixturesBundle\LiipTestFixturesBundle(),
            new AutoMapperPlus\AutoMapperPlusBundle\AutoMapperPlusBundle(),
        ];
    }

    protected function configureContainer(ContainerBuilder $c, LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config.yml');
    }

    protected function configureRoutes(RoutingConfigurator $routes)
    {
        $routes->import(__DIR__.'/config/routing.yml');
    }

    public function getCacheDir(): string
    {
        return sys_get_temp_dir().'/TalavMediaBundle/cache/'.$this->getEnvironment();
    }

    public function getLogDir(): string
    {
        return sys_get_temp_dir().'/TalavMediaBundle/logs';
    }
}
