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

use App\Entity\Action;
use App\Provider\ThemeProvider;
use Gibbon\Core;
use Gibbon\Database\Connection;
use Gibbon\Database\MySqlConnector;
use Gibbon\Domain\System\Module;
use Gibbon\Domain\System\Theme;
use Gibbon\Services\ErrorHandler;
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
     * @var ThemeProvider
     */
    private $themeProvider;

    /**
     * @var string
     */
    private $version;

    /**
     * GibbonManager constructor.
     * @param RequestStack $stack
     */
    public function __construct(RequestStack $stack, ThemeProvider $themeProvider, string $version)
    {
        $this->request = $stack->getCurrentRequest();
        $this->themeProvider = $themeProvider;
        $this->version = $version;
    }

    /**
     * execute
     * @return Response|null
     */
    public function execute(): ?Response
    {
        self::$instance = $this;
        $gibbon = $this->container->get('config');
        $gibbon->wrapVersion = $this->version;
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

        $this->prepareAction()
            ->prepareModule()
            ->prepareTheme()
            ->preparePage();

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

        $address = $this->request->query->get('q') ?? $this->request->request->get('address') ?? '';

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

    /**
     * prepareTheme
     * @return GibbonManager
     * @throws \Exception
     */
    private function prepareTheme(): self
    {
        $session = $this->request->getSession();
        $repository = $this->themeProvider->getRepository();

        if ($session->has('gibbonThemeIDPersonal')) {
            $data = ['gibbonThemeID' => $session->get('gibbonThemeIDPersonal')];
        } else {
            $data = ['active' => 'Y'];
        }

        $themeData = $repository->findOneBy($data);

        $session->set('gibbonThemeID', $themeData->getID() ?? 001);
        $session->set('gibbonThemeName', $themeData->getName() ?? 'Default');

        $theme = $themeData ? new Theme($themeData->toArray()) : null;

        $this->container->set('theme', $theme);
        return $this;
    }

    /**
     * preparePage
     * @return GibbonManager
     */
    private function preparePage(): self
    {
        $session = $this->request->getSession();

        $pageTitle = $session->get('organisationNameShort').' - '.$session->get('systemName');
        if ($session->has('module')) {
            $pageTitle .= ' - '.__($session->get('module'));
        }
        $page = $this->container->get('page');

        $page->setParams([
            'title'   => $pageTitle,
            'address' => $session->get('address'),
            'action'  => $this->container->get('action'),
            'module'  => $this->container->get('module'),
            'theme'   => $this->container->get('theme'),
        ]);

        $this->container->set('errorHandler', new ErrorHandler($session->get('installType'), $page));
        return $this;
    }

    /**
     * prepareAction
     * @return GibbonManager
     * @throws \Exception
     */
    private function prepareAction(): self
    {
        $repository = $this->themeProvider->getRepository(Action::class);
        $session = $this->request->getSession();

        $actionData = $repository->findOneByURLListModuleNameRoleID(
            '%'.$session->get('action').'%',
            $session->get('module'),
            $session->get('gibbonRoleIDCurrent')
        );
        $actionData = $actionData ? $actionData->toArray() : [];

        $this->container->set('action', $actionData);
        return $this;
    }

    /**
     * prepareModule
     * @return GibbonManager
     * @throws \Exception
     */
    private function prepareModule(): self
    {
        $repository = $this->themeProvider->getRepository(\App\Entity\Module::class);
        $session = $this->request->getSession();

        if (null !== $moduleData = $repository->findOneBy(['name' => $session->get('module')]))
        {
            $this->container->set('module', new Module($moduleData->toArray()));
        } else {
            $this->container->set('module', null);
        }

        return $this;
    }
}