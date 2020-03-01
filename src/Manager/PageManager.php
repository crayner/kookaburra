<?php
/**
 * Created by PhpStorm.
 *
 * Kookaburra
 * (c) 2020 Craig Rayner <craig@craigrayner.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * User: craig
 * Date: 20/02/2020
 * Time: 15:17
 */

namespace App\Manager;

use App\Manager\Entity\BreadCrumbs;
use App\Manager\Entity\HeaderManager;
use App\Provider\ProviderFactory;
use App\Twig\FastFinder;
use App\Twig\IdleTimeout;
use App\Twig\MainMenu;
use App\Twig\MinorLinks;
use App\Twig\SidebarContent;
use App\Util\Format;
use App\Util\GlobalHelper;
use App\Util\ImageHelper;
use App\Util\LocaleHelper;
use App\Util\TranslationsHelper;
use App\Util\UrlGeneratorHelper;
use Kookaburra\SystemAdmin\Entity\Action;
use Kookaburra\SystemAdmin\Entity\Module;
use Kookaburra\UserAdmin\Util\SecurityHelper;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Class PageManager
 * @package App\Manager
 */
class PageManager
{
    /**
     * @var RequestStack
     */
    private $stack;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var MinorLinks
     */
    private $minorLinks;

    /**
     * @var MainMenu
     */
    private $mainMenu;

    /**
     * @var array
     */
    private $headerLinks;

    /**
     * @var AuthorizationCheckerInterface
     */
    private $checker;

    /**
     * @var string|null
     */
    private $route;

    /**
     * @var SidebarContent
     */
    private $sidebar;

    /**
     * @var BreadCrumbs
     */
    private $breadCrumbs;

    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var IdleTimeout
     */
    private $idleTimeout;

    /**
     * @var FastFinder
     */
    private $fastFinder;

    /**
     * @var Format
     */
    private $format;

    /**
     * @var array
     */
    private $translations = [];

    /**
     * PageManager constructor.
     * @param RequestStack $stack
     * @param MinorLinks $minorLinks
     * @param MainMenu $mainMenu
     * @param AuthorizationCheckerInterface $checker
     * @param SidebarContent $sidebar
     * @param BreadCrumbs $breadCrumbs
     * @param Environment $twig
     * @param IdleTimeout $idleTimeout
     * @param FastFinder $fastFinder
     * @param GlobalHelper $helper
     * @param Format $format
     * @throws \Exception
     */
    public function __construct(
        RequestStack $stack,
        MinorLinks $minorLinks,
        MainMenu $mainMenu,
        AuthorizationCheckerInterface $checker,
        SidebarContent $sidebar,
        BreadCrumbs $breadCrumbs,
        Environment $twig,
        IdleTimeout $idleTimeout,
        FastFinder $fastFinder,
        GlobalHelper $helper,
        Format $format
    ) {
        $this->stack = $stack;
        $this->minorLinks = $minorLinks;
        $this->mainMenu = $mainMenu;
        $this->checker = $checker;
        $this->sidebar = $sidebar;
        $this->breadCrumbs = $breadCrumbs;
        $this->twig = $twig;
        $this->minorLinks->execute();
        $this->idleTimeout =  $idleTimeout;
        $this->fastFinder = $fastFinder;
        $this->format = $format;
        
        $this->configurePage();
    }

    /**
     * @return RequestStack
     */
    public function getStack(): RequestStack
    {
        return $this->stack;
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        if (null === $this->request)
            $this->request = $this->getStack()->getCurrentRequest();
        return $this->request;
    }

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * getSession
     * @return SessionInterface
     */
    public function getSession(): ?SessionInterface
    {
        if (null === $this->session && $this->getRequest() && $this->getRequest()->hasSession())
            return $this->session = $this->getRequest()->getSession();
        return $this->session;
    }

    /**
     * getLocale
     * @return string
     */
    public function getLocale()
    {
        return LocaleHelper::getLocale();
    }

    /**
     * writeParameters
     * @return array
     */
    public function writeProperties(): array
    {
        $this->addTranslation('Loading');
        return [
            'locale' => $this->getLocale(),
            'rtl' => LocaleHelper::getRtl($this->getLocale()),
            'bodyImage' => ImageHelper::getBackgroundImage(),
            'minorLinks' => $this->minorLinks->getContent(),
            'headerDetails' => $this->getHeaderDetails(),
            'route' => $this->getRoute(),
            'action' => $this->getRoute() !== 'home' ? $this->getAction() : [],
            'module' => $this->getRoute() !== 'home' ? $this->getModule() : [],
            'url' => UrlGeneratorHelper::getUrl($this->getRoute(), $this->getRequest()->get('_route_params') ?: []),
            'footer' => $this->getFooter(),
            'translations' => $this->getTranslations(),
        ];
    }

    /**
     * getHeaderDetails
     * @return array
     */
    public function getHeaderDetails(): array
    {
        $details = new HeaderManager($this->getRequest(), $this->checker, $this->mainMenu);
        return $details->toArray();
    }

    /**
     * getAction
     * @return array
     */
    private function getAction(): array
    {
        return SecurityHelper::getActionFromRoute($this->getRoute());
    }

    /**
     * getAction
     * @return array
     */
    private function getModule(): array
    {
        return SecurityHelper::getModuleFromRoute($this->getRoute());
    }

    /**
     * @return string|null
     */
    public function getRoute(): ?string
    {
        if (null === $this->route)
            $this->route = $this->getRequest()->get('_route');
        return $this->route;
    }

    /**
     * getFooter
     * @return array
     */
    private function getFooter(): array
    {
        return [
            'translations' => [
                'Kookaburra' => TranslationsHelper::translate('Kookaburra'),
                'Created under the' => TranslationsHelper::translate('Created under the'),
                'Powered by' => TranslationsHelper::translate('Powered by'),
                'from a fork of' => TranslationsHelper::translate('from a fork of'),
                'licence' => TranslationsHelper::translate('licence'),
            ],
            'footerLogo' => ImageHelper::getAbsoluteImageURL('File', '/themes/Default/img/logoFooter.png'),
            'footerThemeAuthor' => TranslationsHelper::translate('Theme {name} by {person}', ['{person}' => 'Craig Rayner', '{name}' => 'Default']),
            'year' => date('Y'),
        ];
    }

    /**
     * render
     * @param array $options
     * @return JsonResponse
     */
    public function render(array $options): JsonResponse
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults(
            [
                'content' => '',
                'pagination' => [],
                'breadCrumbs' => '',
                'sidebar' => [],
                'containers' => [],
                'title' => $this->getRoute() !== 'home' ? TranslationsHelper::translate($this->getAction()['name'], [], str_replace(' ', '', $this->getModule()['name'])) : '',
            ]
        );

        return new JsonResponse(array_merge($resolver->resolve($options), $this->getSidebar()->toArray(), $this->getBreadCrumbs()));
    }

    /**
     * @return SidebarContent
     */
    public function getSidebar(): SidebarContent
    {
        return $this->sidebar;
    }

    /**
     * createBreadcrumbs
     * @param string $title
     * @param array $crumbs
     * @return PageManager
     */
    public function createBreadcrumbs(string $title, array $crumbs): PageManager
    {
        $result = [];
        $result['title'] = TranslationsHelper::translate($title);
        $moduleName = $this->getModule()['name'];
        $domain = str_replace(' ','',$moduleName);
        $result['crumbs'] = $crumbs;
        $result['baseURL'] = strtolower(str_replace(' ','_',$moduleName));
        $result['domain'] = $domain;
        $result['module'] = $moduleName;

        $this->breadCrumbs->create($result);
        return $this;
    }

    /**
     * hasBreadCrumbs
     * @return bool
     */
    private function hasBreadCrumbs(): bool
    {
        return $this->breadCrumbs->isValid();
    }

    /**
     * getBreadCrumbs
     * @return array
     */
    public function getBreadCrumbs(): array
    {
        return ['breadCrumbs' => ($this->hasBreadCrumbs() ? $this->breadCrumbs->toArray() : '')];
    }

    /**
     * writeIdleTimeout
     * @return array
     */
    public function writeIdleTimeout(): array
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY'))
            return [];

        $this->idleTimeout->execute();
        return $this->idleTimeout->getAttributes()->toArray();
    }

    /**
     * Checks if the attributes are granted against the current authentication token and optionally supplied subject.
     *
     * @param $attributes
     * @param null $subject
     * @return bool
     */
    protected function isGranted($attributes, $subject = null): bool
    {
        return $this->checker->isGranted($attributes, $subject);
    }

    /**
     * writeFastFinder
     * @return array
     * @throws \Exception
     */
    public function writeFastFinder(): array
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY'))
            return [];

        $this->fastFinder->execute();
        return $this->fastFinder->getAttributes()->toArray();
    }

    /**
     * configurePage
     */
    public function configurePage(): void
    {
        $this->format->setupFromSession($this->getSession());
        $this->setAddress();
    }

    /**
     * setAddress
     * @param bool $useAddress
     */
    private function setAddress(): void
    {
        $this->getRequest()->attributes->set('address', false);
        $this->getRequest()->attributes->set('module', false);
        $this->getRequest()->attributes->set('action', false);
        // Legacy Settings
        $this->getSession()->set('address', '');
        $this->getSession()->set('module', '');
        $this->getSession()->set('action', '');
        $route = $this->getRequest()->attributes->get('_route');
        if (false === strpos($route, '_ignore_address') && null !== $route) {
            $this->getRequest()->attributes->set('address', $route);
            $this->setModule($route, $this->getRequest());
            $this->getSession()->set('address', $route);
        }
    }

    /**
     * setModule
     * @param string $address
     */
    private function setModule(string $address)
    {
        if (substr($address, -4) === '.php')
        {
            $moduleName = SecurityHelper::getModuleName($address);
        } else {
            $moduleName = explode('__', $address)[0];
            $moduleName = ucwords(str_replace('_', ' ', $moduleName));
        }
        $module = ProviderFactory::getRepository(Module::class)->findOneByName($moduleName);
        $this->getRequest()->attributes->set('module', $module ?: false);
        $this->getSession()->set('module', $module ? $module->getName() : '');
        if (null !== $module)
            $this->setAction($address, $module);
    }

    /**
     * setAction
     * @param string $address
     * @param Module $module
     */
    private function setAction(string $address, Module $module)
    {
        $address = strpos($address, '__') !== false ? explode('__', $address)[1] : basename($address);
        $action = ProviderFactory::getRepository(Action::class)->findOneByModuleContainsURL($module, $address);
        $this->getRequest()->attributes->set('action', $action);
        $this->getSession()->set('action', $action ? $address : '');
    }

    /**
     * isNotReadyForJSON
     * @param bool $testing
     * @return bool
     */
    public function isNotReadyForJSON(bool $testing = true)
    {
        return $this->getRequest()->getContentType() !== 'json' && $testing;
    }

    /**
     * getBaseResponse
     * @return Response
     */
    public function getBaseResponse() {

        try {
            $content = $this->twig->render('react_base.html.twig',
                [
                    'page' => $this,
                ]
            );
        } catch (LoaderError | RuntimeError | SyntaxError $e) {
            $content = '<h1>Failed!</h1>';
        }
        return new Response($content);
    }

    /**
     * @return array
     */
    public function getTranslations(): array
    {
        return $this->translations;
    }

    /**
     * Translations.
     *
     * @param string $id
     * @param array $options
     * @param string $domain
     * @return PageManager
     */
    public function addTranslation(string $id, array $options = [], string $domain = 'messages'): PageManager
    {
        $this->translations[$id] = TranslationsHelper::translate($id,$options,$domain);
        return $this;
    }
}