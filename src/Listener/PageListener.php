<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 29/07/2019
 * Time: 07:42
 */

namespace App\Listener;


use App\Manager\ScriptManager;
use App\Twig\FastFinder;
use App\Twig\MainMenu;
use App\Twig\MinorLinks;
use App\Twig\ModuleMenu;
use App\Twig\Sidebar;
use App\Util\CacheHelper;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class PageListener implements EventSubscriberInterface
{
    /**
     * @var Sidebar
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
     * @param Sidebar $sideBar
     * @param MainMenu $mainMenu
     * @param ModuleMenu $moduleMenu
     * @param MinorLinks $minorLinks
     * @param TranslatorInterface $trans
     * @param RouterInterface $router
     */
    public function __construct(
        Sidebar $sideBar,
        MainMenu $mainMenu,
        ModuleMenu $moduleMenu,
        MinorLinks $minorLinks,
        FastFinder $fastFinder,
        TranslatorInterface $trans,
        RouterInterface $router,
        ScriptManager $scriptManager,
        CacheHelper $cacheHelper,
        TokenStorageInterface $token
    ) {
        $this->sideBar = $sideBar;
        $this->mainMenu = $mainMenu;
        $this->moduleMenu = $moduleMenu;
        $this->minorLinks = $minorLinks;
        $this->fastFinder = $fastFinder;
        $this->fastFinder->setScriptManager($scriptManager)->setRouter($router)->setTranslator($trans);
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
        $this->cacheHelper::setSession($event->getRequest()->getSession());
        $this->sideBar->execute();
        $this->mainMenu->execute();
        $this->moduleMenu->execute();
        $this->minorLinks->execute();
        $this->fastFinder->setToken($this->token)->execute();
    }
}