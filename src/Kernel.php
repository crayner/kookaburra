<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\RouteCollectionBuilder;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    private const CONFIG_EXTS = '.{php,xml,yaml,yml}';

    public function registerBundles(): iterable
    {
        $contents = require $this->getProjectDir().'/config/bundles.php';
        foreach ($contents as $class => $envs) {
            if ($envs[$this->environment] ?? $envs['all'] ?? false) {
                yield new $class();
            }
        }
    }

    public function getProjectDir(): string
    {
        return \dirname(__DIR__);
    }

    public function getPublicDir(): string
    {
        return $this->getProjectDir() . '/public';
    }

    protected function configureContainer(ContainerBuilder $container, LoaderInterface $loader): void
    {
        $container->addResource(new FileResource($this->getProjectDir().'/config/bundles.php'));
        $container->setParameter('container.dumper.inline_class_loader', true);
        $container->setParameter('current_year', date('Y'));
        $container->setParameter('current_month', date('m'));
        $container->setParameter('kernel.public_dir', $this->getPublicDir());
        $container->setParameter('upload_path', $this->getPublicDir() . DIRECTORY_SEPARATOR . date('Y') . DIRECTORY_SEPARATOR . date('m'));
        $confDir = $this->getProjectDir().'/config';

        $loader->load($confDir.'/{packages}/*'.self::CONFIG_EXTS, 'glob');
        $loader->load($confDir.'/{packages}/'.$this->environment.'/**/*'.self::CONFIG_EXTS, 'glob');
        $loader->load($confDir.'/{services}'.self::CONFIG_EXTS, 'glob');
        $loader->load($confDir.'/{services}_'.$this->environment.self::CONFIG_EXTS, 'glob');


        if (!realpath($confDir . '/packages/kookaburra.yaml'))
            $this->temporaryParameters($container);

        $timezone = $container->getParameter('timezone', 'UTC');

        date_default_timezone_set($timezone ?: 'UTC');
        putenv("TZ=".($timezone ?: 'UTC'));
    }

    protected function configureRoutes(RouteCollectionBuilder $routes): void
    {
        $confDir = $this->getProjectDir().'/config';

        $routes->import($confDir.'/{routes}/'.$this->environment.'/**/*'.self::CONFIG_EXTS, '/', 'glob');
        $routes->import($confDir.'/{routes}/*'.self::CONFIG_EXTS, '/', 'glob');
        $routes->import($confDir.'/{routes}'.self::CONFIG_EXTS, '/', 'glob');
    }

    /**
     * temporaryParameters
     * @param ContainerBuilder $container
     */
    private function temporaryParameters(ContainerBuilder $container)
    {
        $container->setParameter('timezone', 'UTC');
        $container->setParameter('absoluteURL', null);
        $container->setParameter('databaseServer', null);
        $container->setParameter('databaseUsername', null);
        $container->setParameter('databasePassword', null);
        $container->setParameter('databaseName', null);
        $container->setParameter('databasePort', null);
        $container->setParameter('security.hierarchy.roles', null);
        $container->setParameter('installed', false);
        $container->setParameter('installation', []);
    }
}
