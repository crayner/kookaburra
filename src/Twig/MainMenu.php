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
 * Date: 29/07/2019
 * Time: 12:08
 */

namespace App\Twig;

use App\Manager\ScriptManager;
use App\Util\TranslationsHelper;
use App\Util\UrlGeneratorHelper;
use Kookaburra\SystemAdmin\Entity\Action;
use Kookaburra\SystemAdmin\Entity\Module;
use App\Provider\ProviderFactory;
use Kookaburra\UserAdmin\Manager\SecurityUser;
use Kookaburra\UserAdmin\Util\SecurityHelper;
use Kookaburra\UserAdmin\Util\UserHelper;
use Symfony\Component\HttpFoundation\UrlHelper;

/**
 * Class MainMenu
 * @package App\Twig
 */
class MainMenu implements ContentInterface
{
    use ContentTrait;

    /**
     * @var ScriptManager|null
     */
    private $scriptManager;

    /**
     * execute
     */
    public function execute(): void 
    {
        $this->content = false;
        $user = UserHelper::getSecurityUser();
        $menuMainItems = false;

        if ($user instanceof SecurityUser) {
            if (! $this->getSession()->has('menuMainItems') || false === $this->getSession()->get('menuMainItems')) {
                $menuMainItems = ProviderFactory::create(Module::class)->selectModulesByRole($this->getSession()->get('gibbonRoleIDCurrent'));
                foreach ($menuMainItems as $category => &$items) {
                    foreach ($items as &$item) {
                        if (strpos($item['entryURL'], '.php') === false) {
                            $route = Action::getRouteName($item['name'], $item['entryURL']);
                            $altRoute = Action::getRouteName($item['name'], $item['alternateEntryURL']);
                            $item['route'] = SecurityHelper::isRouteAccessible($route) ? $route : $altRoute;
                            $item['url'] = false;
                            $moduleName = SecurityHelper::getModuleName($item['route']);
                            $item['name'] = TranslationsHelper::translate($item['name'], [], $moduleName);
                            $item['href'] = UrlGeneratorHelper::getUrl($item['route']);
                        } else {
                            $modulePath = '/modules/' . $item['name'];
                            $entryURL = SecurityHelper::isActionAccessible($modulePath . '/' . $item['entryURL'])
                                ? $item['entryURL']
                                : $item['alternateEntryURL'];
                            $item['route'] = false;
                            $item['url'] = $this->getSession()->get('absoluteURL') . '/?q=' . $modulePath . '/' . $entryURL;
                            $item['href'] = $item['url'];
                        }
                    }
                }
                foreach($menuMainItems as $q=>$w) {
                    unset($menuMainItems[$q]);
                    $menuMainItems[TranslationsHelper::translate($q)] = $w;
                }
                $this->getSession()->set('menuMainItems', $menuMainItems);
            } else {
                $menuMainItems = $this->getSession()->get('menuMainItems', false);
            }
            $this->addAttribute('menuMainItems', $menuMainItems);
        }
        $this->getScriptManager()->addAppProp('headerMenu', ['menu' => $menuMainItems, 'translations' => ['Home' => TranslationsHelper::translate('Home')]]);
    }

    /**
     * @return ScriptManager|null
     */
    public function getScriptManager(): ?ScriptManager
    {
        return $this->scriptManager;
    }

    /**
     * ScriptManager.
     *
     * @param ScriptManager|null $scriptManager
     * @return MainMenu
     */
    public function setScriptManager(?ScriptManager $scriptManager): MainMenu
    {
        $this->scriptManager = $scriptManager;
        return $this;
    }

}