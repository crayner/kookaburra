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
 * Date: 28/07/2019
 * Time: 09:07
 */

namespace App\Listener;

use App\Util\GlobalHelper;
use Kookaburra\SystemAdmin\Entity\Action;
use Kookaburra\SystemAdmin\Entity\Module;
use App\Provider\ProviderFactory;
use App\Util\Format;
use Kookaburra\UserAdmin\Util\SecurityHelper;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class AddressModuleActionListener
 * @package App\Listener
 */
class AddressModuleActionListener implements EventSubscriberInterface
{
    /**
     * @var Format
     */
    private $format;

    /**
     * AddressModuleActionListener constructor.
     * @param Format $format
     */
    public function __construct(Format $format, GlobalHelper $helper)
    {
        $this->format = $format;
    }


    /**
     * getSubscribedEvents
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => ['onKernelController', -8],
        ];
    }
    /**
     * onKernelController
     * @param ControllerEvent $event
     */
    public function onKernelController(ControllerEvent $event): void
    {
        $request = $event->getRequest();
        if (strpos($request->get('_route'),'install__') === 0)
            return;
        if ($request->get('_route') === 'module_action_build')
            return;
        $this->format->setupFromSession($request->getSession());
        if ($request->query->has('q'))
            return;
        if ($request->request->has('address')) {
            $this->setAddress($request, true);
            return;
        }
        $this->setAddress($request);
    }

    /**
     * setAddress
     * @param Request $request
     */
    private function setAddress(Request $request, bool $useAddress = false): void
    {
        $request->attributes->set('address', false);
        $request->attributes->set('module', false);
        $request->attributes->set('action', false);
        // Legacy Settings
        $request->getSession()->set('address', '');
        $request->getSession()->set('module', '');
        $request->getSession()->set('action', '');
        if ($useAddress) {
            $address = $request->request->get('address');
            $request->attributes->set('address', $address);
            $request->getSession()->set('address', $address);
            $this->setModule($address, $request);
        }
        $route = $request->attributes->get('_route');
        if (false === strpos($route, '_ignore_address') && null !== $route) {
            $request->attributes->set('address', $route);
            $this->setModule($route, $request);
            $request->getSession()->set('address', $route);
        }
    }

    /**
     * setModule
     * @param string $address
     * @param Request $request
     */
    private function setModule(string $address, Request $request)
    {
        if (substr($address, -4) === '.php')
        {
            $moduleName = SecurityHelper::getModuleName($address);
        } else {
            $moduleName = explode('__', $address)[0];
            $moduleName = ucwords(str_replace('_', ' ', $moduleName));
        }
        $module = ProviderFactory::getRepository(Module::class)->findOneByName($moduleName);
        $request->attributes->set('module', $module ?: false);
        $request->getSession()->set('module', $module ? $module->getName() : '');
        if (null !== $module)
            $this->setAction($address, $module, $request);
    }

    /**
     * setAction
     * @param string $address
     * @param Module $module
     * @param Request $request
     */
    private function setAction(string $address, Module $module, Request $request)
    {
        $address = strpos($address, '__') !== false ? explode('__', $address)[1] : basename($address);
        $action = ProviderFactory::getRepository(Action::class)->findOneByModuleContainsURL($module, $address);
        $request->attributes->set('action', $action);
        $request->getSession()->set('action', $action ? $address : '');
    }
}