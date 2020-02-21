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
 * Date: 29/07/2019
 * Time: 07:42
 */

namespace App\Listener;

use App\Manager\ScriptManager;
use Kookaburra\UserAdmin\Manager\SecurityUser;
use App\Twig\FastFinder;
use App\Twig\IdleTimeout;
use App\Twig\MainMenu;
use App\Twig\MinorLinks;
use App\Twig\ModuleMenu;
use App\Twig\SidebarContent;
use App\Util\CacheHelper;
use App\Util\UrlGeneratorHelper;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class PageListener implements EventSubscriberInterface
{
    /**
     * @var SidebarContent
     */
    private $sideBar;

    /**
     * @var MainMenu
     */
    private $mainMenu;

    /**
     * @var ModuleMenu
     */
    private $moduleMenu;

    /**
     * @var MinorLinks
     */
    private $minorLinks;

    /**
     * @var CacheHelper
     */
    private  $cacheHelper;

    /**
     * @var TokenStorageInterface
     */
    private  $token;

    /**
     * PageListener constructor.
     * @param SidebarContent $sideBar
     * @param MainMenu $mainMenu
     * @param ModuleMenu $moduleMenu
     * @param MinorLinks $minorLinks
     * @param FastFinder $fastFinder
     * @param IdleTimeout $idleTimeout
     * @param TranslatorInterface $trans
     * @param RouterInterface $router
     * @param ScriptManager $scriptManager
     * @param CacheHelper $cacheHelper
     * @param TokenStorageInterface $token
     */
    public function __construct(
        SidebarContent $sideBar,
        MainMenu $mainMenu,
        ModuleMenu $moduleMenu,
        MinorLinks $minorLinks,
        FastFinder $fastFinder,
        IdleTimeout $idleTimeout,
        TranslatorInterface $trans,
        RouterInterface $router,
        ScriptManager $scriptManager,
        CacheHelper $cacheHelper,
        TokenStorageInterface $token
    ) {
        $this->sideBar = $sideBar;
        $this->mainMenu = $mainMenu;
        $this->moduleMenu = $moduleMenu;
        $this->moduleMenu->setTranslator($trans);
        $this->minorLinks = $minorLinks;
        $this->fastFinder = $fastFinder;
        $this->idleTimeout = $idleTimeout;
        $this->fastFinder->setScriptManager($scriptManager)->setRouter($router)->setTranslator($trans);
        $this->idleTimeout->setScriptManager($scriptManager)->setRouter($router)->setTranslator($trans);
        $this->cacheHelper = $cacheHelper;
        $this->token = $token;
    }

    /**
     * getSubscribedEvents
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => ['buildPageContent', -16],
        ];
    }

    /**
     * buildMainMenu
     * @param ControllerEvent $event
     */
    public function buildPageContent(ControllerEvent $event)
    {
        $this->sideBar->execute();
        if (! in_array($event->getRequest()->attributes->get('_route'),['legacy'])) {
            $this->cacheHelper::setSession($event->getRequest()->getSession());
            $this->mainMenu->execute();
            $this->minorLinks->execute();
            $this->fastFinder->setToken($this->token)->execute();
            if ($this->token->getToken() && $this->token->getToken()->getUser() instanceof SecurityUser) {
                $this->fastFinder->getScriptManager()->addEncoreEntryScriptTag('notificationTray');
                $this->idleTimeout->execute();
            }
        }
        if(!$this->token->getToken() || !$this->token->getToken()->getUser() instanceof SecurityUser) {
            $route = $event->getRequest()->attributes->get('_route') ?: 'legacy';
            $routeParams = $event->getRequest()->attributes->get('_route_params') ?: [];
            $event->getRequest()->getSession()->set('address', UrlGeneratorHelper::getPath($route,$routeParams));
        }
    }
}