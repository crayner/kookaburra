<?php
/**
 * Created by PhpStorm.
 *
 * bilby
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 25/06/2019
 * Time: 17:13
 */

namespace App\Manager;

use Gibbon\Contracts\Database\Connection;
use Gibbon\Core;
use Gibbon\Database\MySqlConnector;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\HttpFoundation\Response;

class GibbonManager implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @var Core
     */
    private static $gibbon;

    /**
     * @var string
     */
    private static $guid;

    public function execute()
    {
        $gibbon =     $this->container->get('config');
        self::setGibbon($gibbon);
        $gibbon->session = $this->container->get('session');
        $gibbon->locale = $this->container->get('locale');
        self::setGuid($gibbon->getConfig('guid'));

        //todo  Installation Testing will return installation Response if necessary

        // Initialize using the database connection
        if ($gibbon->isInstalled()) {

            $mysqlConnector = new MySqlConnector();
            if ($pdo = $mysqlConnector->connect($gibbon->getConfig())) {
                $this->container->set('db', $pdo);
                $this->container->set(Connection::class, $pdo);
                $pdo->getConnection();

                $gibbon->initializeCore($this->container);
            } else {
                // We need to handle failed database connections after install. Display an error if no connection
                // can be established. Needs a specific error page once header/footer is split out of index.
                if (!$gibbon->isInstalling()) {
                    $content = $this->container->get('twig')->render('legacy/error.html.twig');
                    return new Response($content);
                }
            }
        }
    }

    /**
     * @return Core
     */
    public static function getGibbon(): Core
    {
        return self::$gibbon;
    }

    /**
     * @param Core $gibbon
     */
    public static function setGibbon(Core $gibbon): void
    {
        self::$gibbon = $gibbon;
    }

    /**
     * @return string
     */
    public static function getGuid(): string
    {
        return self::$guid;
    }

    /**
     * @param string $guid
     */
    public static function setGuid(string $guid): void
    {
        self::$guid = $guid;
        self::$gibbon->session->setGuid($guid);
    }
}