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

use Gibbon\Core;
use Gibbon\Database\Connection;
use Gibbon\Database\MySqlConnector;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
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
     * @var Connection
     */
    private $connection;

    /**
     * @var \PDO
     */
    private $pdo;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var GibbonManager
     */
    private static $instance;

    /**
     * GibbonManager constructor.
     * @param RequestStack $stack
     */
    public function __construct(RequestStack $stack)
    {
        $this->request = $stack->getCurrentRequest();
    }

    /**
     * execute
     * @return Response|null
     */
    public function execute(): ?Response
    {
        self::$instance = $this;
        $gibbon = $this->container->get('config');
        self::setGibbon($gibbon);

        $gibbon->session = $this->prepareSession($gibbon->getConfig('guid'));
        $gibbon->locale = $this->container->get('locale');
        self::setGuid($gibbon->getConfig('guid'));

        //todo  Installation Testing will return installation Response if necessary

        // Initialize using the database connection
        if ($gibbon->isInstalled()) {

            $mysqlConnector = new MySqlConnector();
            if ($pdo = $mysqlConnector->connect($gibbon->getConfig())) {
                $this->container->set('db', $pdo);
//                $this->container->set(Connection::class, $pdo);
                self::setConnection($pdo);
                self::setPDO($pdo->getConnection());

                $gibbon->initializeCore($this->container);
            } else {
                // We need to handle failed database connections after install. Display an error if no connection
                // can be established. Needs a specific error page once header/footer is split out of index.
                if (!$gibbon->isInstalling()) {
                    return self::returnErrorResponse();
                }
            }
        }
        return null;
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
     * @return Connection
     */
    public static function getConnection(): Connection
    {
        return self::$instance->connection;
    }

    /**
     * @param Connection $connection
     */
    public static function setConnection(Connection $connection): void
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

    /**
     * prepareSession
     * @return SessionManager
     */
    private function prepareSession($guid): SessionManager
    {
        $session = $this->container->get('session');
        // Backwards compatibility for external modules
        $this->guid = $this->container->has('config')? $this->container->get('config')->guid() : $guid;

        $session->setGuid($this->guid);

        // Detect the current module from the GET 'q' param. Fallback to the POST 'address',
        // which is currently used in many Process pages.
        // TODO: replace this logic when switching to routing.

        $address = $this->request->query->get['q'] ?? $this->request->request->get['address'] ?? '';

        $session->set('address', $address);
        $session->set('module', $address ? getModuleName($address) : '');
        $session->set('action', $address ? getActionName($address) : '');
        $session->setGuid($this->guid);

        if (!$session->has('absoluteURL') || $session->get('absoluteURL') !== $this->container->getParameter('absoluteURL')) {
            $session->set('absoluteURL', $this->container->getParameter('absoluteURL'));
        }
        if (!$session->has('absolutePath')) {
            $session->set('absolutePath', $this->container->getParameter('kernel.project_dir') . '/public');
        }

        return $session;
    }

    /**
     * @return \PDO
     */
    public static function getPDO(): \PDO
    {
        return self::$instance->pdo;
    }

    /**
     * @param \PDO $pdo
     */
    public static function setPDO(\PDO $pdo): void
    {
        self::$instance->pdo = $pdo;
    }

    /**
     * getContainer
     * @return Container
     */
    public static function getContainer(): Container
    {
        return self::$instance->container;
    }

    /**
     * getRequest
     * @return Request
     */
    public static function getRequest(): Request
    {
        return self::$instance->request;
    }
}