<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 6/10/2019
 * Time: 09:07
 */

namespace App\Router;

use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Routing\RouteCollection;

/**
 * Class KookaburraLoader
 * @package App\Router
 */
class KookaburraLoader extends Loader
{
    /**
     * @var bool
     */
    private $isLoaded = false;

    /**
     * load
     * @param mixed $resource
     * @param null $type
     * @return RouteCollection
     */
    public function load($resource, $type = null)
    {
        if (true === $this->isLoaded)
            throw new \RuntimeException('Do not add the "kookaburra" loader twice');

        $routes = new RouteCollection();

        $finder = new Finder();
        $bundles = $finder->directories()->depth('0')->in(__DIR__ . '/../../vendor/kookaburra');
        if (false === $finder->hasResults())
            return $routes;

        foreach($bundles as $bundle)
        {
            $resource = realpath($bundle.'/src/Resources/config/routes.yaml');

            if (false === $resource)
                throw new \RuntimeException($bundle .'/src/Resources/config/routes.yaml must be a valid file.');

            $type = 'yaml';

            $importedRoutes = $this->import($resource, $type);

            $routes->addCollection($importedRoutes);
        }

        $this->isLoaded = true;

        return $routes;
    }

    /**
     * supports
     * @param mixed $resource
     * @param null $type
     * @return bool
     */
    public function supports($resource, $type = null)
    {
        return 'kookaburra' === $type;
    }
}