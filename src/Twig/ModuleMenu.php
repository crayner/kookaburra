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
use App\Manager\ScriptManager;
use App\Provider\ProviderFactory;
use Symfony\Component\Routing\Router;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ModuleMenu implements ContentInterface
{
    use ContentTrait;

    /**
     * @var ScriptManager
     */
    private $scriptManager;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var TranslatorInterface
     */
    private $translator;

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
                    $item['category'] = $this->translate($item['category']);
                    $urlList = array_map('trim', explode(',', $item['URLList']));
                    $item['active'] = in_array($request->attributes->get('action')->getEntryURL(), $urlList);
                    $item['route'] = strpos($item['entryURL'], '.php') === false ? $currentModule->getEntryURLFullRoute($item['entryURL']) : false;
                    $item['url'] = $this->checkURL($item);
                }
            }

            $request->getSession()->set('menuModuleItems', $menuModuleItems);
            $this->addAttribute('menuModuleItems', $menuModuleItems);
            $this->addAttribute('ModuleMenu', true);
            $request->getSession()->set('menuModuleName', $currentModule->getName());
            $data = ['data' => $menuModuleItems];
            $data['trans_module_menu'] = $this->translate('Module Menu');
            $data['sidebar'] = $this->isValid();
            $this->getScriptManager()->addAppProp('menuModule', $data);
        } else {
            $request->getSession()->forget(['menuModuleItems', 'menuModuleName']);
        }
    }

    /**
     * @return ScriptManager
     */
    public function getScriptManager(): ScriptManager
    {
        return $this->scriptManager;
    }

    /**
     * ScriptManager.
     *
     * @param ScriptManager $scriptManager
     * @return ModuleMenu
     */
    public function setScriptManager(ScriptManager $scriptManager): ModuleMenu
    {
        $this->scriptManager = $scriptManager;
        return $this;
    }

    /**
     * @return RouterInterface
     */
    public function getRouter(): RouterInterface
    {
        return $this->router;
    }

    /**
     * Router.
     *
     * @param RouterInterface $router
     * @return ModuleMenu
     */
    public function setRouter(RouterInterface $router): ModuleMenu
    {
        $this->router = $router;
        return $this;
    }

    /**
     * @return TranslatorInterface
     */
    public function getTranslator(): TranslatorInterface
    {
        return $this->translator;
    }

    /**
     * Translator.
     *
     * @param TranslatorInterface $translator
     * @return ModuleMenu
     */
    public function setTranslator(TranslatorInterface $translator): ModuleMenu
    {
        $this->translator = $translator;
        return $this;
    }

    /**
     * translate
     * @param string $key
     * @param array|null $params
     * @param string|null $domain
     * @return string
     */
    private function translate(string $key, ?array $params = [], ?string $domain = 'gibbon'): string
    {
        return $this->getTranslator()->trans($key, $params, $domain);
    }

    /**
     * checkURL
     * @param array $link
     * @return mixed|string
     */
    public function checkURL(array $link)
    {
        if (isset($link['route']) && false !== $link['route'])
        {
            return $this->router->generate($link['route'], [], Router::ABSOLUTE_URL);
        }

        if (false === strpos($link['entryURL'], '.php')) {
            $route = $link['url'];
            $route = explode('q=/modules/', $route);
            if (count($route) !== 2)
                return $link['url'];
            $route = strtolower(str_replace(' ', '_', substr($route[1], 0, strpos($route[1], '/')))) . '__' . $link['entryURL'];
            $url = $this->router->generate($route, [], Router::ABSOLUTE_URL);
            return $url;
        }
        return $link['url'];
    }

}