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
 * Date: 21/12/2019
 * Time: 08:29
 */

namespace App\Controller;

use App\Provider\ProviderFactory;
use Kookaburra\SystemAdmin\Entity\Module;
use Kookaburra\SystemAdmin\Entity\NotificationEvent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Yaml\Yaml;

/**
 * Class ModuleBuilderController
 * @package App\Controller
 */
class ModuleBuilderController extends AbstractController
{
    /**
     * Module Builder
     * @Route("/module/action/build/", name="module_action_build")
     * @param ParameterBagInterface $bag
     */
    public function build(ParameterBagInterface $bag)
    {
        $module = ProviderFactory::create(Module::class)->findOneBy(['name' => 'System Admin']); //School Admin
        dump('Change the search detail here to map a module/actions/permissions. Currently ' . $module->getName());
        $result = [];
        $x['name'] = $module->getName();
        $x['description'] = $module->getDescription();
        $x['entryURL'] = $module->getEntryURL();
        $x['type'] = $module->getType();
        $x['active'] = $module->getActive();
        $x['category'] = $module->getCategory();
        $x['version'] = $module->getVersion();
        $x['author'] = $module->getAuthor();
        $x['url'] = $module->getUrl();
        $result['version'] = '0.0.00';
        $result['name'] = $module->getName();;
        $result['module'] = $x;

        foreach($module->getActions() as $action)
        {
            $x = [];
            $x['name'] = $action->getName();
            $x['precedence'] = $action->getPrecedence();
            $x['category'] = $action->getCategory();
            $x['description'] = $action->getDescription();
            $x['URLList'] = $action->getURLList();
            $x['entryURL'] = $action->getEntryURL();
            $x['entrySidebar'] = $action->getEntrySidebar();
            $x['menuShow'] = $action->getMenuShow();
            $x['defaultPermissionAdmin'] = $action->getDefaultPermissionAdmin();
            $x['defaultPermissionTeacher'] = $action->getDefaultPermissionTeacher();
            $x['defaultPermissionStudent'] = $action->getDefaultPermissionStudent();
            $x['defaultPermissionParent'] = $action->getDefaultPermissionParent();
            $x['defaultPermissionSupport'] = $action->getDefaultPermissionSupport();
            $x['categoryPermissionStaff'] = $action->getCategoryPermissionStaff();
            $x['categoryPermissionStudent'] = $action->getCategoryPermissionStudent();
            $x['categoryPermissionParent'] = $action->getCategoryPermissionParent();
            $x['categoryPermissionOther'] = $action->getCategoryPermissionOther();


            foreach($action->getPermissions() as $permission) {
                $x['permissions'][] = $permission->getRole()->getName();
            }
            $result['module']['actions'][$action->getName()] = $x;
        }
        $publicDir = $bag->get('kernel.public_dir');


        // Notifications
        $provider = ProviderFactory::create(NotificationEvent::class);
/*        $notificationEvents = $provider->getRepository()->findBy([], ['moduleName' => 'ASC', 'actionName' => 'ASC', 'event' => 'ASC']);

        $connection = $provider->getEntityManager()->getConnection();
        $connection->beginTransaction();

        $connection->query('DELETE FROM `gibbonNotificationEvent`');
        $connection->query('ALTER TABLE `gibbonNotificationEvent` AUTO_INCREMENT = 1');
        $connection->commit();
        $provider->getEntityManager()->clear();

        foreach($notificationEvents as $q=>$event)
        {
            $mod = ProviderFactory::getRepository(Module::class)->findOneBy(['name' => $event->getModuleName()]);
            $action = null;
            foreach($mod->getActions()->toArray() as $w)
            {
                if ($w->getName() === $event->getActionName())
                {
                    $action = $w;
                    break;
                }
            }
            $event->setId(null)->setAction($action)->setModule($mod)->setModuleName(null)->setActionName(null);
        }

        foreach($notificationEvents as $event) {
            $provider->getEntityManager()->persist($event);
            $provider->getEntityManager()->flush();
        }
*/
        $notificationEvents = $provider->getRepository()->findBy(['module' => $module], ['moduleName' => 'ASC', 'actionName' => 'ASC', 'event' => 'ASC']);

        $events = [];
        foreach($notificationEvents as $w)
        {
            $event = [];
            $event['event'] = $w->getEvent();
            $event['module'] = $w->getModuleName();
            $event['action'] = $w->getActionName();
            $event['scopes'] = $w->getScopes();
            $event['active'] = $w->isActive() ? 'Y' : 'N';
            $events[$w->getEvent()] = $event;
        }

        $result['events'] = $events;

        file_put_contents($publicDir . '/' . $module->getName() . '.yaml', Yaml::dump($result, 8));

        dd(Yaml::dump($result, 8));
    }
}