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
use App\Entity\SchoolYear;
use App\Provider\ProviderFactory;
use App\Session\GibbonSession;
use App\Util\SecurityHelper;
use Gibbon\Core;
use Gibbon\Database\Connection;
use Gibbon\Database\MySqlConnector;
use Gibbon\Domain\System\Module;
use Gibbon\Domain\System\Theme;
use Gibbon\Services\ErrorHandler;
use Gibbon\Services\Format;
use Gibbon\sqlConnection;
use Gibbon\View\Page;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

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
     * @var sqlConnection
     */
    private $connection2;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var GibbonManager
     */
    private static $instance;

    /**
     * @var ProviderFactory
     */
    private $providerFactory;

    /**
     * @var string
     */
    private $version;

    /**
     * @var Page|null
     */
    private $page;

    /**
     * @var bool
     */
    private $initialised = false;

    /**
     * GibbonManager constructor.
     * @param RequestStack $stack
     */
    public function __construct(RequestStack $stack, ProviderFactory $providerFactory, string $version)
    {
        $this->request = $stack->getCurrentRequest();
        $this->providerFactory = $providerFactory;
        $this->version = $version;
        self::$instance = $this;
    }

    /**
     * execute
     * @return Response|null
     */
    public function execute(): ?Response
    {
        if ($this->initialised)
        {
            return null;
        }
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
            if ($connection = $mysqlConnector->connect($gibbon->getConfig())) {
                $this->container->set('db', $connection);
//                $this->container->set(Connection::class, $pdo);
                self::setConnection2($connection);
                self::setPDO($connection->getConnection());
                $sqlConnector = new sqlConnection();
                $sqlConnector->setPdo($connection->getConnection());
                self::setConnection($sqlConnector);

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
            ->preparePage()
            ->prepareSchoolYear()
        ;
        $this->initialised = true;
        return null;
    }

    /**
     * @return Core
     */
    public static function getGibbon(): Core
    {
        if (null === self::$instance->gibbon) {
            self::$instance->execute();
        }
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
        if (!isset(self::$instance))
        {
            return guid();
        }
        return isset(self::$instance->guid) ? self::$instance->guid : self::$instance->request->getSession()->guid();
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
    public static function getConnection2(): Connection
    {
        return self::$instance->connection2;
    }

    /**
     * @param Connection $connection
     */
    public static function setConnection2(Connection $connection): void
    {
        self::$instance->connection2 = $connection;
    }

    /**
     * @return sqlConnection
     */
    public static function getConnection(): sqlConnection
    {
        return self::$instance->connection;
    }

    /**
     * @param Connection $connection
     */
    public static function setConnection(sqlConnection $connection): void
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
     * @param $guid
     * @return GibbonSession
     */
    private function prepareSession($guid): GibbonSession
    {
        $session = $this->request->getSession();
        // Backwards compatibility for external modules
        $this->guid = $this->container->has('config')? $this->container->get('config')->guid() : $guid;

        $session->setGuid($this->guid);

        // Detect the current module from the GET 'q' param. Fallback to the POST 'address',
        // which is currently used in many Process pages.
        // TODO: replace this logic when switching to routing.

        $address = $this->getAddress();

        $session->set('address', $address);
        $session->set('module', $address ? SecurityHelper::getModuleName($address) : '');
        $session->set('action', $address ? SecurityHelper::getActionName($address) : '');
        $session->setGuid($this->guid);

        if (!$session->has('absoluteURL') || $session->get('absoluteURL') !== $this->container->getParameter('absoluteURL')) {
            $session->set('absoluteURL', $this->container->getParameter('absoluteURL'));
        }
        if (!$session->has('absolutePath')) {
            $session->set('absolutePath', $this->container->getParameter('kernel.project_dir') . '/public');
        }

        Format::setupFromSession($session);
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
        $repository = $this->providerFactory::getRepository(\App\Entity\Theme::class);

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
            'action'  => ViewFactory::getAction(),
            'module'  => ViewFactory::getModule(),
            'theme'   => $this->container->get('theme'),
        ]);

        $this->container->set('errorHandler', new ErrorHandler($session->get('installType'), $page));
        $this->page = $page;

        return $this;
    }

    /**
     * prepareAction
     * @return GibbonManager
     * @throws \Exception
     */
    private function prepareAction(): self
    {
        $repository = $this->providerFactory::getRepository(Action::class);
        $session = $this->request->getSession();

        $actionData = $repository->findOneByURLListModuleNameRoleID(
            '%'.$session->get('action', '').'%',
            $session->get('module', ''),
            $session->get('gibbonRoleIDCurrent', 0)
        );
        $actionData = $actionData ? $actionData->toArray() : null;

        ViewFactory::setAction($actionData);

        return $this;
    }

    /**
     * prepareModule
     * @return GibbonManager
     * @throws \Exception
     */
    private function prepareModule(): self
    {
        $repository = $this->providerFactory::getRepository(\App\Entity\Module::class);
        $session = $this->request->getSession();

        if (null !== $moduleData = $repository->findOneBy(['name' => $session->get('module')]))
        {
            ViewFactory::setModule(new Module($moduleData->toArray()));
        } else {
            ViewFactory::setModule(null);
        }

        return $this;
    }

    /**
     * injectAddress
     * @param string $address
     * @return GibbonManager
     */
    public function injectAddress(string $address): self
    {
        $session = $this->request->getSession();

        $session->set('address', $address);
        $session->set('module', $address ? getModuleName($address) : '');
        $session->set('action', $address ? getActionName($address) : '');

        return $this->prepareAction()
            ->prepareModule()
            ->preparePage();
    }

    /**
     * @return Page|null
     */
    public function getPage(): ?Page
    {
        return $this->page;
    }

    private function prepareSchoolYear(): self
    {
        $session = $this->request->getSession();
        if (!$session->has('gibbonSchoolYearIDCurrent')) {
            $schoolYear = ProviderFactory::getRepository(SchoolYear::class)->findOneByStatus('Current');
            if ($schoolYear) {
                $session->set('gibbonSchoolYearIDCurrent', $schoolYear->getId());
                $session->set('gibbonSchoolYearNameCurrent', $schoolYear->getName());
                $session->set('gibbonSchoolYearSequenceNumberCurrent', $schoolYear->getSequenceNumber());
                $session->set('schoolYearCurrent', $schoolYear);
            }
        }
        return $this;
    }

    /**
     * getSession
     * @return SessionInterface
     */
    public static function getSession(): SessionInterface
    {
        return self::getRequest()->getSession();
    }

    public static function getVersion(): string
    {
        return self::$instance->version;
    }

    /**
     * getService
     * @param string $name
     * @return mixed
     */
    public static function getService(string $name)
    {
        return self::$instance->container->get($name);
    }

    /**
     * getAddress
     * @return string
     */
    private function getAddress(): string
    {
        $address = $this->request->query->get('q') ?? $this->request->request->get('address') ?? '';

        return $address;
    }
}

require_once __DIR__.'/../../Gibbon/functions.php';