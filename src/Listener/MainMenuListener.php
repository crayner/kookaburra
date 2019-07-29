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
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class MainMenuListener implements EventSubscriberInterface
{
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * MainMenuListener constructor.
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * getSubscribedEvents
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => ['buildMainMenu', -16],
        ];
    }

    /**
     * buildMainMenu
     * @param ControllerEvent $event
     */
    public function buildMainMenu(ControllerEvent $event)
    {
        $user = $this->tokenStorage->getToken() ? $this->tokenStorage->getToken()->getUser() : null;
        if (! $user instanceof SecurityUser)
            return;

        $request = $event->getRequest();

        if ($request->attributes->has('module') && false !== $request->attributes->get('module'))
        {
            $currentModule = $request->attributes->get('module');
            $lastModule = $request->getSession()->get('menuModuleName', null);

            if (! $request->getSession()->has('menuModuleItems') || $currentModule->getName() !== $lastModule)
            {
                $menuModuleItems = ProviderFactory::create(Module::class)->selectModuleActionsByRole($currentModule, $request->getSession()->get('gibbonRoleIDCurrent'));
            } else {
                $menuModuleItems = $request->getSession()->get('menuModuleItems');
            }

            foreach ($menuModuleItems as $category => &$items) {
                foreach ($items as &$item) {
                    $urlList = array_map('trim', explode(',', $item['URLList']));
                    $item['active'] = in_array($request->attributes->get('action')->getEntryURL(), $urlList);
                    $item['route'] = strpos($item['entryURL'], '.php') === false ? $currentModule->getEntryURLFullRoute($item['entryURL']) : false;
                    $item['url'] = $request->get('absoluteURL') . '/?q=/modules/'
                        .$item['moduleName'].'/'. $item['entryURL'];
                }
            }

            $request->getSession()->set('menuModuleItems', $menuModuleItems);
            $request->attributes->set('menuModuleItems', $menuModuleItems);
            $request->getSession()->set('menuModuleName', $currentModule->getName());
        } else {
            $request->getSession()->forget(['menuModuleItems', 'menuModuleName']);
        }
    }
}