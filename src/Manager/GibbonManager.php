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
use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\HttpFoundation\Response;

class GibbonManager implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @var Core
     */
    private $gibbon;

    /**
     * @var string
     */
    private $guid;

    /**
     * @var \PDO
     */
    private $connection;

    /**
     * @var GibbonManager
     */
    private static $instance;

    public function execute()
    {
        self::$instance = $this;
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
                self::setConnection($pdo->getConnection());

                $gibbon->initializeCore($this->container);
            } else {
                // We need to handle failed database connections after install. Display an error if no connection
                // can be established. Needs a specific error page once header/footer is split out of index.
                if (!$gibbon->isInstalling()) {
                    return self::returnErrorResponse();
                }
            }
        }
    }

    /**
     * @return Core
     */
    public static function getGibbon(): Core
    {
        return self::$instance->gibbon;
    }

    /**
     * @param Core $gibbon
     */
    public static function setGibbon(Core $gibbon): void
    {
        self::$instance->gibbon = $gibbon;
    }

    /**
     * @return string
     */
    public static function getGuid(): string
    {
        return self::$instance->guid;
    }

    /**
     * @param string $guid
     */
    public static function setGuid(string $guid): void
    {
        self::$instance->guid = $guid;
        self::$instance->gibbon->session->setGuid($guid);
    }

    /**
     * @return \PDO
     */
    public static function getConnection(): \PDO
    {
        return self::$instance->connection;
    }

    /**
     * @param \PDO $connection
     */
    public static function setConnection(\PDO $connection): void
    {
        self::$instance->connection = $connection;
    }

    /**
     * returnErrorResponse
     * @param string|null $extendedError
     * @return Response
     */
    public static function returnErrorResponse(string $extendedError = null): Response
    {
        $content = static::$instance->container->get('twig')->render('legacy/error.html.twig',
            [
                'extendedError' => $extendedError,
                'manager' => static::$instance,
            ]
        );
        return new Response($content);
    }
}