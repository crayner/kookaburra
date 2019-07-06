<?php
/**
 * Created by PhpStorm.
 *
 * bilby
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 2/07/2019
 * Time: 15:17
 */

namespace App\Provider;

use App\Entity\Module;
use App\Entity\Setting;
use App\Manager\Traits\EntityTrait;

class ModuleProvider implements EntityProviderInterface
{
    use EntityTrait;

    /**
     * @var string
     */
    private $entityName = Module::class;

    /**
     * selectModulesByRole
     * @param $gibbonRoleID
     * @return mixed
     */
    public function selectModulesByRole($roleID)
    {
        $settingProvider = ProviderFactory::create(Setting::class);
        $mainMenuCategoryOrder = $settingProvider->getSettingByScope('System', 'mainMenuCategoryOrder');

        $result = $this->getRepository()->findModulesByRole($roleID);
        $sorted = [];
        foreach(explode(',', $mainMenuCategoryOrder) as $category)
        {
            if (!isset($sorted[$category])) {
                $sorted[$category] = [];
            }
            foreach($result as $module)
            {
                $module['textDomain'] = $module['type'] === 'Core' ? null : $module['name'];
                if ($module['category'] === $category)
                {
                    $sorted[$category][] = $module;
                }
            }
        }

        return $sorted;
    }

    /**
     * selectModuleActionsByRole
     * @param int $moduleID
     * @param int $roleID
     * @return array
     * @throws \Exception
     */
    public function selectModuleActionsByRole(int $moduleID, int $roleID)
    {
        $result = $this->getRepository()->findModuleActionsByRole($moduleID, $roleID);

        $categories = [];

        foreach($result as $q=>$module)
        {
            $module['textDomain'] = $module['type'] === 'Core' ? null : $module['moduleName'];
            $name = explode('_',$module['name']);
            $module['name'] = $name[0];
            $result[$q] = $module;
            $categories[$module['category']][] = $module;
        }

        return $categories;
    }
}