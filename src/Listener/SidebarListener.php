<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 8/11/2019
 * Time: 16:09
 */

namespace App\Listener;

use App\Twig\ModuleMenu;
use App\Twig\Sidebar\Login;
use App\Twig\Sidebar\Welcome;
use App\Twig\SidebarContent;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class SidebarListener
 * @package App\Listener
 */
class SidebarListener implements EventSubscriberInterface
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var SidebarContent
     */
    private $sidebar;

    /**
     * @var ParameterBagInterface
     */
    private $params;

    /**
     * @var ModuleMenu
     */
    private $moduleMenu;

    /**
     * SidebarListener constructor.
     * @param RequestStack $stack
     * @param SidebarContent $sidebar
     */
    public function __construct(RequestStack $stack, SidebarContent $sidebar, ParameterBagInterface $params, ModuleMenu $moduleMenu)
    {
        $this->stack = $stack;
        $this->sidebar = $sidebar;
        $this->params = $params;
        $this->moduleMenu = $moduleMenu;
    }

    /**
     * getSubscribedEvents
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => ['injectSidebarContent', -32],
        ];
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
        if (null === $this->request) {
            $this->request = $this->getStack()->getCurrentRequest();
        }
        return $this->request;
    }

    /**
     * @return SidebarContent
     */
    public function getSidebar(): SidebarContent
    {
        return $this->sidebar;
    }

    /**
     * injectSidebarContent
     */
    public function injectSidebarContent()
    {
        $route = $this->getRequest()->get('_route');
        $moduleName = ($x = explode('__', $route))[0];
        if (!$this->isInstalled() || $moduleName === 'install') {
            $this->getSidebar()->addContent(new Welcome());
            return ;
        }

        if (count($x) < 2 && !in_array($moduleName, ['home']))
            return ;

        switch ($route) {
            case 'user_admin__registration_public':
            case 'home':
                $this->getSidebar()->addContent(new Login());
                break;
            default:
                $this->getSidebar()->addContent($this->getModuleMenu()->execute());
        }

    }

    /**
     * @return bool
     */
    public function isInstalled(): bool
    {
        return $this->getParams()->get('installed');
    }

    /**
     * @return ParameterBagInterface
     */
    public function getParams(): ParameterBagInterface
    {
        return $this->params;
    }

    /**
     * @return ModuleMenu
     */
    public function getModuleMenu(): ModuleMenu
    {
        return $this->moduleMenu;
    }
}