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
use App\Entity\Role;
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
     * @param Role|int $roleID
     * @return mixed
     */
    public function selectModulesByRole($roleID, bool $byCategory = true)
    {
        $settingProvider = ProviderFactory::create(Setting::class);
        $mainMenuCategoryOrder = $settingProvider->getSettingByScope('System', 'mainMenuCategoryOrder');

        $roleID = $roleID instanceof Role ? $roleID->getId() : $roleID;

        $result = $this->getRepository()->findModulesByRole($roleID);
        $sorted = [];
        foreach(explode(',', $mainMenuCategoryOrder) as $category)
        {
            if ($byCategory && !isset($sorted[$category])) {
                $sorted[$category] = [];
            }
            foreach($result as $module)
            {
                $module['textDomain'] = $module['type'] === 'Core' ? null : $module['name'];
                $name = explode('_',$module['name']);
                $module['name'] = $name[0];
                if ($module['category'] === $category && $byCategory)
                {
                    $sorted[$category][] = $module;
                } elseif (!$byCategory) {
                    $sorted[] = $module;
                }
            }
        }

        return $sorted;
    }

    /**
     * selectModuleActionsByRole
     * @param Module|int $moduleID
     * @param Role|int $roleID
     * @return array
     * @throws \Exception
     */
    public function selectModuleActionsByRole($module, $role)
    {
        if (null === $role || '' === $role || 0 === $role)
            return [];

        if (!$module instanceof Module)
            $module = $this->getRepository()->find($module);

        if (!$role instanceof Role)
            $role = $this->getRepository(Role::class)->find($role);

        $result = $this->getRepository()->findModuleActionsByRole($module, $role);

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