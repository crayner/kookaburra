<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 22/07/2019
 * Time: 16:52
 */

namespace App\Twig\Extension;


use App\Entity\I18n;
use App\Entity\Person;
use App\Manager\ScriptManager;
use App\Provider\I18nProvider;
use App\Provider\ProviderFactory;
use App\Twig\MainMenu;
use App\Twig\ModuleMenu;
use App\Twig\Sidebar;
use App\Util\Format;
use Doctrine\DBAL\Exception\ConnectionException;
use Doctrine\DBAL\Exception\TableNotFoundException;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Router;
use Symfony\Component\Routing\RouterInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class PageExtension extends AbstractExtension
{
    /**
     * @var I18nProvider
     */
    private $i18nProvider;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var RequestStack
     */
    private $stack;

    /**
     * @var Sidebar
     */
    private $sidebar;

    /**
     * @var MainMenu
     */
    private $mainMenu;

    /**
     * @var RouterInterface 
     */
    private $router;

    /**
     * @var ScriptManager
     */
    private $scriptManager;

    /**
     * @var ModuleMenu
     */
    private $moduleMenu;

    /**
     * PageExtension constructor.
     *
     * @param Sidebar $sidebar
     * @param SessionInterface $session
     * @param RequestStack $stack
     * @param RouterInterface $router
     * @param MainMenu $mainMenu
     * @param ScriptManager $scriptManager
     * @param ProviderFactory $providerFactory
     */
    public function __construct(
        Sidebar $sidebar,
        SessionInterface $session,
        RequestStack $stack,
        RouterInterface $router,
        MainMenu $mainMenu,
        ModuleMenu $moduleMenu,
        ScriptManager $scriptManager,
        ProviderFactory $providerFactory,
        Format $format
    )  {
        $this->i18nProvider = $providerFactory::create(I18n::class);
        $this->session = $session;
        $this->stack = $stack;
        $this->sidebar = $sidebar;
        $this->router = $router;
        $this->mainMenu = $mainMenu;
        $this->moduleMenu = $moduleMenu;
        $this->scriptManager = $scriptManager;
        $this->format = $format;
    }

    /**
     * getFunctions
     * @return array|\Twig_Function[]
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('rightToLeft', [$this, 'findLocaleRightToLeft']),
            new TwigFunction('houseIDLogo', [$this, 'houseIDLogo']),
            new TwigFunction('minorLinks', [$this, 'minorLinks']),
            new TwigFunction('notificationTray', [$this, 'notificationTray']),
            new TwigFunction('getSidebar', [$this, 'getSidebar']),
            new TwigFunction('getMainMenu', [$this, 'getMainMenu']),
            new TwigFunction('getModuleMenu', [$this, 'getModuleMenu']),
            new TwigFunction('content', [$this, 'content']),
            new TwigFunction('alerts', [$this, 'alerts']),
            new TwigFunction('getThemeName', [$this, 'getThemeName']),
            new TwigFunction('checkURL', [$this, 'checkURL']),
            new TwigFunction('getEncoreEntryScriptTags', [$this->scriptManager, 'getEncoreEntryScriptTags']),
            new TwigFunction('getAppProps', [$this->scriptManager, 'getAppProps']),
            new TwigFunction('formatUsing', [$this, 'formatUsing']),
            new TwigFunction('getAlertBar', [$this, 'getAlertBar']),
            new TwigFunction('getPageScripts', [$this->scriptManager, 'getPageScripts']),
        ];
    }

    /**
     * findLocaleRightToLeft
     * @return bool
     * @throws \Exception
     */
    public function findLocaleRightToLeft(): bool
    {
        try {
            return $this->i18nProvider->getRepository()->findLocaleRightToLeft();
        } catch (ConnectionException $e) {
            return false;
        } catch (TableNotFoundException $e) {
            return false;
        }
    }

    /**
     * houseIDLogo
     * @return string|null
     */
    public function houseIDLogo(): ?string
    {
        return $this->session->has('gibbonHouseIDLogo') ? $this->session->get('gibbonHouseIDLogo') : null;
    }

    /**
     * minorLinks
     * @return string|null
     */
    public function minorLinks(): ?string
    {
        return null;
    }

    /**
     * notificationTray
     * @return string|null
     */
    public function notificationTray(): ?string
    {
        return null;
    }

    /**
     * content
     * @return bool
     */
    public function content()
    {
        return false;
    }

    /**
     * breadcrumbs
     * @return bool
     */
    public function alerts()
    {
        return [];
    }

    /**
     * getThemeName
     * @return string
     */
    public function getThemeName(): string
    {
        return $this->session->get('gibbonThemeName', 'default');
    }

    /**
     * checkURL
     * @param array $link
     * @return mixed|string
     */
    public function checkURL(array $link)
    {
        if (isset($link['route']) && false !== $link['route'])
        {
            return $this->router->generate($link['route'], [], Router::ABSOLUTE_URL);
        }

        if (false === strpos($link['entryURL'], '.php')) {
            $route = $link['url'];
            $route = explode('q=/modules/', $route);
            if (count($route) !== 2)
                return $link['url'];
            $route = strtolower(str_replace(' ', '_', substr($route[1], 0, strpos($route[1], '/')))) . '__' . $link['entryURL'];
            $url = $this->router->generate($route, [], Router::ABSOLUTE_URL);
            return $url;
        }
        return $link['url'];
    }

    /**
     * formatUsing
     * @param $method
     * @param mixed ...$args
     * @return mixed
     */
    public function formatUsing($method, ...$args)
    {
        return Format::$method($args);
    }

    /**
     * getAlertBar
     * @param Person $person
     * @param string $divExtras
     * @param bool $div
     * @param bool $large
     * @return mixed
     */
    public function getAlertBar(Person $person, string $divExtras = '', bool $div = true, bool $large = false)
    {
        $provider = ProviderFactory::create(Person::class);
        return $provider->getAlertBar($person, $divExtras, $div, $large);
    }

    /**
     * getSideBar
     * @return Sidebar
     */
    public function getSideBar(): Sidebar
    {
        return $this->sidebar;
    }

    /**
     * getMainMenu
     * @return MainMenu
     */
    public function getMainMenu(): MainMenu
    {
        return $this->mainMenu;
    }

    /**
     * getModuleMenu
     * @return ModuleMenu
     */
    public function getModuleMenu(): ModuleMenu
    {
        return $this->moduleMenu;
    }
}