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


use App\Entity\Module;
use App\Provider\ProviderFactory;
use App\Security\SecurityUser;
use App\Twig\MainMenu;
use App\Twig\ModuleMenu;
use App\Twig\Sidebar;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

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
     * PageListener constructor.
     * @param Sidebar $sideBar
     * @param MainMenu $mainMenu
     * @param ModuleMenu $moduleMenu
     */
    public function __construct(Sidebar $sideBar, MainMenu $mainMenu, ModuleMenu $moduleMenu)
    {
        $this->sideBar = $sideBar;
        $this->mainMenu = $mainMenu;
        $this->moduleMenu = $moduleMenu;
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
        $this->sideBar->getContent();
        $this->mainMenu->getContent();
        $this->moduleMenu->getContent();
    }
}