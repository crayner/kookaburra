<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 29/07/2019
 * Time: 13:43
 */

namespace App\Twig;


use App\Entity\Module;
use App\Provider\ProviderFactory;

class ModuleMenu implements ContentInterface
{
    use ContentTrait;

    /**
     * execute
     */
    public function execute(): void
    {
        $request = $this->getRequest();

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
            $this->addContent('menuModuleItems', $menuModuleItems);
            $request->getSession()->set('menuModuleName', $currentModule->getName());
        } else {
            $request->getSession()->forget(['menuModuleItems', 'menuModuleName']);
        }
    }
}