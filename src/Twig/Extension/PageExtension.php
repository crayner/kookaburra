<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * User: craig
 * Date: 22/07/2019
 * Time: 16:52
 */

namespace App\Twig\Extension;

use App\Entity\I18n;
use Kookaburra\SchoolAdmin\Entity\AcademicYear;
use Kookaburra\UserAdmin\Entity\Person;
use App\Entity\Setting;
use App\Exception\MissingClassException;
use App\Manager\EntityInterface;
use App\Manager\ScriptManager;
use App\Provider\I18nProvider;
use App\Provider\ProviderFactory;
use App\Twig\MainMenu;
use App\Twig\MinorLinks;
use App\Twig\ModuleMenu;
use App\Twig\Sidebar\Photo;
use App\Twig\SidebarContent;
use App\Util\Format;
use App\Util\ImageHelper;
use Kookaburra\UserAdmin\Util\UserHelper;
use Doctrine\DBAL\Exception\ConnectionException;
use Doctrine\DBAL\Exception\TableNotFoundException;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Intl\Languages;
use Symfony\Component\Routing\Router;
use Symfony\Component\Routing\RouterInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Class PageExtension
 * @package App\Twig\Extension
 */
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
     * @var SidebarContent
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
     * @var MinorLinks
     */
    private $minorLinks;

    /**
     * PageExtension constructor.
     *
     * @param SidebarContent $sidebar
     * @param SessionInterface $session
     * @param RequestStack $stack
     * @param RouterInterface $router
     * @param MainMenu $mainMenu
     * @param ScriptManager $scriptManager
     * @param ProviderFactory $providerFactory
     */
    public function __construct(
        SidebarContent $sidebar,
        SessionInterface $session,
        RequestStack $stack,
        RouterInterface $router,
        MainMenu $mainMenu,
        ModuleMenu $moduleMenu,
        ScriptManager $scriptManager,
        ProviderFactory $providerFactory,
        Format $format,
        ImageHelper $imageHelper,
        MinorLinks $minorLinks
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
        $this->minorLinks = $minorLinks;
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
            new TwigFunction('checkURL', [$this->moduleMenu, 'checkURL']),
            new TwigFunction('getEncoreEntryScriptTags', [$this->scriptManager, 'getEncoreEntryScriptTags']),
            new TwigFunction('getAppProps', [$this->scriptManager, 'getAppProps']),
            new TwigFunction('formatUsing', [$this, 'formatUsing']),
            new TwigFunction('getAlertBar', [$this, 'getAlertBar']),
            new TwigFunction('getPageScripts', [$this->scriptManager, 'getPageScripts']),
            new TwigFunction('getPageStyles', [$this->scriptManager, 'getPageStyles']),
            new TwigFunction('getEncoreEntryCSSFiles', [$this->scriptManager, 'getEncoreEntryCSSFiles']),
            new TwigFunction('getToggleScripts', [$this->scriptManager, 'getToggleScripts']),
            new TwigFunction('pageManager', [$this, 'pageManager']),
            new TwigFunction('getAcademicYears', [$this, 'getAcademicYears']),
            new TwigFunction('getActiveLanguages', [$this, 'getActiveLanguages']),
            new TwigFunction('getBackgroundImage', [$this, 'getBackgroundImage']),
            new TwigFunction('version_compare', [$this, 'version_compare']),
            new TwigFunction('asset_exists', [$this, 'asset_exists']),
            new TwigFunction('getYesNo', [$this, 'getYesNo']),
            new TwigFunction('getLanguageName', [$this, 'getLanguageName']),
            new TwigFunction('getAbsoluteImageURL', [$this, 'getAbsoluteImageURL']),
            new TwigFunction('getRelativeImageURL', [$this, 'getRelativeImageURL']),
            new TwigFunction('displayImage', [$this, 'displayImage']),
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
        return $this->session->has('gibbonHouseIDLogo') ? $this->session->get('gibbonHouseIDLogo') : '';
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
        return strtolower($this->session->get('gibbonThemeName', 'default'));
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
     * getSidebar
     * @return SidebarContent
     */
    public function getSidebar(): SidebarContent
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


    /**
     * pageManager
     * @param string $name
     * @return mixed
     * @throws MissingClassException
     */
    public function pageManager(string $name)
    {
        if (property_exists($this, $name))
            return $this->$name;
        throw new MissingClassException(sprintf('The class "%s" was not available to the "%s" twig extension.', $name, get_class($this)));
    }

    /**
     * getAcademicYears
     * @return array
     */
    public function getAcademicYears(): array
    {
        return ProviderFactory::getRepository(AcademicYear::class)->findBy([], ['firstDay' => 'ASC', 'lastDay' => 'ASC']);
    }

    /**
     * getActiveLangauges
     * @return array
     */
    public function getActiveLanguages(): array
    {
        return ProviderFactory::getRepository(I18n::class)->findByActive();
    }

    /**
     * getBackgroundImage
     * @param string $default
     * @return string
     * @throws \Exception
     */
    public function getBackgroundImage(string $default = '/build/static/backgroundPage.jpg'): string
    {
        if (strpos($this->stack->getCurrentRequest()->get('_route'), 'install__') === 0 )
            return $default;
        $public = realpath(__DIR__ . '/../../../public') . DIRECTORY_SEPARATOR;
        $result = false;
        if ($this->session->has('backgroundImage') && is_file($this->session->get('backgroundImage')))
            return $this->session->get('backgroundImage');
        $user = UserHelper::getCurrentUser();
        if ($user instanceof Person && $user->getPersonalBackground()) {
            $file = realpath(is_file($user->getPersonalBackground()) ? $user->getPersonalBackground() : (is_file($public.$user->getPersonalBackground()) ? $public.$user->getPersonalBackground() : null));
            if (is_file($file)) {
                $file = str_replace([$public, "\\"], ['', '/'], $file);
                $this->session->set('backgroundImage', $file);
                return $file;
            }
        }

        $background = ProviderFactory::create(Setting::class)->getSettingByScopeAsString('System', 'organisationBackground');
        $background = realpath(is_file($background) ? $background : (is_file($public.$background) ? $public.$background : null));
        if (is_file($background)) {
            $background = str_replace([$public, "\\"], ['', '/'], $background);
            $this->session->set('backgroundImage', $background);
            return $background;
        }

        $this->session->set('backgroundImage',  $default);
        return  $default;
    }

    /**
     * version_compare
     * @param string|null $version
     * @param string $required
     * @param string $compare
     * @return bool
     */
    public function version_compare(?string $version, string $required, string $compare = '=='): bool
    {
        return version_compare($version ?: '0.0', $required, $compare);
    }

    /**
     * asset_exists
     * @param string $asset
     * @return bool
     */
    public function asset_exists(string $asset): bool
    {
        return realpath(__DIR__ . '/../../../public/' . ltrim($asset, '/')) ? true : false;
    }

    /**
     * getYesNo
     * @param string $yes
     * @return string
     */
    public function getYesNo(string $yes): string
    {
        return $yes === 'Y' ? 'Yes' : 'No';
    }

    /**
     * getLanguageName
     * @param string $code
     * @return string
     */
    public function getLanguageName(string $code): string
    {
        if (Languages::exists($code))
            return Languages::getName($code);
        return $code;
    }

    /**
     * getAbsoluteImageURL
     * @param string|null $filename
     * @param string $type
     * @return string|null
     */
    public function getAbsoluteImageURL(?string $filename, string $type = 'File')
    {
        return ImageHelper::getAbsoluteImageURL($type, $filename);
    }

    /**
     * getRelativeImageURL
     * @param string|null $filename
     * @return string|null
     */
    public function getRelativeImageURL(?string $filename)
    {
        return ImageHelper::getRelativeImageURL($filename);
    }

    /**
     * fileExists
     * @param string|null $filename
     * @return bool
     */
    public function fileExists(?string $filename): bool
    {
        return file_exists($filename);
    }

    /**
     * displayImage
     * @param EntityInterface $entity
     * @param string $method
     * @param string $size
     * @param string $class
     * @return Photo
     */
    public function displayImage(EntityInterface $entity, string $method, string $size = '75', string $class = ''): Photo
    {
        return ImageHelper::displayImage($entity, $method, $size, $class);
    }
}