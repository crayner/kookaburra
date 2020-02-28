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
use App\Twig\IdleTimeout;
use App\Twig\MainMenu;
use App\Twig\MinorLinks;
use App\Twig\SidebarContent;
use App\Util\ImageHelper;
use App\Util\LocaleHelper;
use App\Util\TranslationsHelper;
use App\Util\UrlGeneratorHelper;
use Kookaburra\UserAdmin\Util\SecurityHelper;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Twig\Environment;

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
     * PageManager constructor.
     * @param RequestStack $stack
     * @param MinorLinks $minorLinks
     * @param MainMenu $mainMenu
     * @param AuthorizationCheckerInterface $checker
     * @param SidebarContent $sidebar
     * @param BreadCrumbs $breadCrumbs
     * @param Environment $twig
     * @param IdleTimeout $idleTimeout
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
        IdleTimeout $idleTimeout
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
     * createResponse
     * @param array $options
     * @return JsonResponse
     */
    public function createResponse(array $options): JsonResponse
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults(
            [
                'content' => '',
                'pagination' => [],
                'breadCrumbs' => '',
                'sidebar' => [],
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
     */
    public function createBreadcrumbs(string $title, array $crumbs)
    {
        $result = [];
        $result['title'] = TranslationsHelper::translate($title);
        $result['crumbs'] = [];
        $moduleName = $this->getModule()['name'];
        $domain = str_replace(' ','',$moduleName);
        foreach($crumbs as $item)
            $item['name'] = TranslationsHelper::translate($item['name'],[],$domain);

        $result['baseURL'] = strtolower(str_replace(' ','_',$moduleName));
        $result['domain'] = $domain;
        $result['module'] = $moduleName;
        $this->breadCrumbs->create($result);
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
    private function getBreadCrumbs(): array
    {
        return ['breadCrumbs' => ($this->hasBreadCrumbs() ? $this->twig->render('components/bread_crumbs.html.twig', ['breadCrumbs' => $this->breadCrumbs]) : '')];
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
}