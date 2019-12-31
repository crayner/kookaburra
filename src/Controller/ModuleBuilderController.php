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
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     * @Route("/module/build/")
     */
    public function build()
    {
        $module = ProviderFactory::create(Module::class)->find(1); //School Admin
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

            foreach($action->getPermissions() as $permission)
                $x['permissions'][] = $permission->getRole()->getName();

            $result['module']['actions'][$action->getName()] = $x;
        }
        dd(Yaml::dump($result, 8));
    }
}