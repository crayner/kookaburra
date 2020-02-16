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
 * Time: 13:43
 */

namespace App\Twig;

use App\Util\GlobalHelper;
use Doctrine\Common\Collections\ArrayCollection;
use Kookaburra\SystemAdmin\Entity\Module;
use App\Manager\ScriptManager;
use App\Provider\ProviderFactory;
use App\Util\UrlGeneratorHelper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Router;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

/**
 * Class ModuleMenu
 * @package App\Twig
 */
class ModuleMenu implements SidebarContentInterface
{
    use SidebarContentTrait;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var string
     */
    private $domain;

    /**
     * @var boolean
     */
    private $showSidebar = true;

    /**
     * @var ArrayCollection
     */
    private $attributes;

    /**
     * @var string
     */
    private $name = 'Module Menu';

    /**
     * @var string
     */
    private $position = 'middle';

    /**
     * execute
     */
    public function execute(): ModuleMenu
    {
        $request = $this->getRequest();

        if ($request->attributes->has('module') && false !== $request->attributes->get('module'))
        {
            $currentModule = $request->attributes->get('module');
            $this->setDomain(str_replace(' ', '', $currentModule->getName()));
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
                    $item['name'] = $this->translate($item['name']);
                    $urlList = array_map('trim', explode(',', $item['URLList']));
                    $item['active'] = $request->attributes->get('action') ? in_array($request->attributes->get('action')->getEntryURL(), $urlList) : false;
                    $item['route'] = strpos($item['entryURL'], '.php') === false ? $currentModule->getEntryURLFullRoute($item['entryURL']) : false;
                    $item['url'] = $this->checkURL($item);
                }
            }

            $request->getSession()->set('menuModuleItems', $menuModuleItems);
            $this->addAttribute('menuModuleItems', $menuModuleItems);
            $this->addAttribute('ModuleMenu', true);
            $request->getSession()->set('menuModuleName', $currentModule->getName());
            $data = ['data' => $menuModuleItems];
            $data['showSidebar'] = $this->isShowSidebar();
            $data['trans_module_menu'] = $this->translate('Module Menu');
            $this->setContent($data);
        } else {
            $request->getSession()->forget(['menuModuleItems', 'menuModuleName']);
        }

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
    private function translate(string $key, ?array $params = [], ?string $domain = null): string
    {
        return $this->getTranslator()->trans($key, $params, $domain ?: $this->getDomain());
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
            return UrlGeneratorHelper::getPath($link['route'], [], Router::ABSOLUTE_URL);
        }

        if (false === strpos($link['entryURL'], '.php')) {
            $route = $link['url'];
            $route = explode('q=/modules/', $route);
            if (count($route) !== 2)
                return $link['url'];
            $route = strtolower(str_replace(' ', '_', substr($route[1], 0, strpos($route[1], '/')))) . '__' . $link['entryURL'];
            $url = UrlGeneratorHelper::getPath($route, [], Router::ABSOLUTE_URL);
            return $url;
        }

        if (!isset($link['url']) && false !== strpos($link['entryURL'], '.php')) {
            $q = '/modules/'. $link['moduleName'] .'/' . $link['entryURL'];
            return UrlGeneratorHelper::getPath('legacy', ['q' => $q], Router::ABSOLUTE_URL);
        }

        if (!isset($link['url'])) dd($link);
        return $link['url'];
    }

    /**
     * @return string
     */
    public function getDomain(): string
    {
        return $this->domain ?: 'messages' ;
    }

    /**
     * Domain.
     *
     * @param string $domain
     * @return ModuleMenu
     */
    public function setDomain(string $domain): ModuleMenu
    {
        $this->domain = $domain;
        return $this;
    }

    /**
     * @return bool
     */
    public function isShowSidebar(): bool
    {
        return $this->showSidebar;
    }

    /**
     * ShowSidebar.
     *
     * @param bool $showSidebar
     * @return ModuleMenu
     */
    public function setShowSidebar(bool $showSidebar): ModuleMenu
    {
        $this->showSidebar = $showSidebar;
        $this->execute();
        return $this;
    }

    /**
     * render
     * @return string
     */
    public function render(array $options): string
    {
        return $this->getTwig()->render('default/sidebar/module_menu.html.twig');
    }

    /**
     * getRequest
     * @return Request
     */
    private function getRequest(): Request
    {
        return GlobalHelper::getRequest();
    }

    /**
     * @return ArrayCollection
     */
    public function getAttributes(): ArrayCollection
    {
        if (null === $this->attributes)
            $this->attributes = new ArrayCollection();
        return $this->attributes;
    }

    /**
     * setAttributes
     * @param ArrayCollection $attributes
     * @return ModuleMenu
     */
    public function setAttributes(ArrayCollection $attributes): ModuleMenu
    {
        $this->attributes = $attributes;
        return $this;
    }

    /**
     * addAttribute
     * @param string $name
     * @param $content
     * @return $this
     */
    public function addAttribute(string $name, $content): ModuleMenu
    {
        $this->getAttributes()->set($name, $content);

        return $this;
    }

    /**
     * hasAttribute
     * @param string $name
     * @return bool
     */
    public function hasAttribute(string $name): bool
    {
        return $this->getAttributes()->containsKey($name);
    }

    /**
     * getAttribute
     * @param string $name
     * @return mixed|null
     */
    public function getAttribute(string $name)
    {
        return $this->hasAttribute($name) ? $this->attributes->get($name) : null;
    }

    /**
     * @var bool
     */
    private $valid = true;

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->valid && $this->getAttributes()->count() > 0;
    }

    /**
     * getValid
     * @return bool
     */
    public function getValid(): bool
    {
        return $this->valid;
    }

    /**
     * Valid.
     *
     * @param bool $valid
     * @return ContentTrait
     */
    public function setValid(bool $valid): ContentTrait
    {
        $this->valid = $valid;
        return $this;
    }

    /**
     * @return Environment
     */
    public function getTwig(): Environment
    {
        return $this->twig;
    }

    /**
     * Twig.
     *
     * @param Environment $twig
     * @return ModuleMenu
     */
    public function setTwig(Environment $twig): ModuleMenu
    {
        $this->twig = $twig;
        return $this;
    }

    /**
     * toArray
     * @return array
     */
    public function toArray(): array
    {
        return $this->getContent();
    }
}