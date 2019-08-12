<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 27/07/2019
 * Time: 08:43
 */

namespace App\Manager\Entity;


use App\Util\UrlGeneratorHelper;
use Doctrine\Common\Collections\ArrayCollection;

class BreadCrumbs
{
    /**
     * @var ArrayCollection|BreadCrumbItem[]
     */
    private $items;

    /**
     * @var null:string
     */
    private $baseURL;

    /**
     * @var
     */
    private $title;

    /**
     * @var bool
     */
    private $legacy = false;

    /**
     * @return BreadCrumbItem[]|ArrayCollection
     */
    public function getItems(): ArrayCollection
    {
        if ($this->isLegacy())
            return $this->getCrumbs();

        if (null === $this->items)
            $this->items = new ArrayCollection();

        return $this->items;
    }

    /**
     * Items.
     *
     * @param BreadCrumbItem[]|ArrayCollection|null $items
     * @return BreadCrumbs
     */
    public function setItems(?ArrayCollection $items)
    {
        $this->items = $items;
        return $this;
    }

    /**
     * addItem
     * @param BreadCrumbItem $item
     * @return BreadCrumbs
     */
    public function addItem(BreadCrumbItem $item): self
    {
        if ($this->getItems()->containsKey($item->getName()))
            return $this;

        $this->items->set($item->getName(),$item);

        return $this;
    }

    /**
     * @return null
     */
    public function getBaseURL()
    {
        return trim($this->baseURL, '/');
    }

    /**
     * BaseURL.
     *
     * @param null $baseURL
     * @return BreadCrumbs
     */
    public function setBaseURL($baseURL): BreadCrumbs
    {
        $this->baseURL = $baseURL;
        return $this->setModule($baseURL);
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Title.
     *
     * @param mixed $title
     * @return BreadCrumbs
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * add
     * @param string $title
     */
    public function create(array $module)
    {
        $this->setItems(new ArrayCollection());
        $item = new BreadCrumbItem();
        $item->setName('Home')->setUri('home');
        $this->addItem($item);
        $this->setTitle($module['title']);
        $this->setBaseURL($module['baseURL']);

        foreach($module['crumbs'] as $crumb) {
            if (false === strpos($crumb['uri'], '__'))
                $crumb['uri'] =  $this->getModule() . '__' . $crumb['uri'];
            $item = new BreadCrumbItem($crumb);
            $this->addItem($item);
        }

        $item = new BreadCrumbItem();
        $item->setName($module['title'])->setUri(null);
        $this->addItem($item);

        return $this->getItems();
    }

    private $crumbs = [];

    /**
     * Add a named route to the trail.
     *
     * @param string $title   Name to display on this route's link
     * @param string $route   URL relative to the trail's BaseURL
     * @param array  $params  Additional URL params to append to the route
     * @return self
     */
    public function add(string $title, string $route = '', array $params = [])
    {
        if (count($this->getCrumbs()) === 0 && $title !== 'Home')
            $this->add('Home', 'home', []);
//{'baseURL': '/departments', 'crumbs': [{uri: '/list/', name: 'Departments'},{uri: '/list/', name: 'View All'}], title: department.name}

        if ($title === 'Home')
        {
            $this->crumbs = ['baseURL' => $this->getBaseURL(), 'crumbs' => ['Home' => UrlGeneratorHelper::getPath('home')], 'title' => $title];
            return $this;
        }

        $this->addCrumb($title, $route, $params);

        return $this->setLegacy(true);
    }

    /**
     * addCrumb
     * @param string $title
     * @param string $route
     * @param array $params
     * @return BreadCrumbs
     */
    private function addCrumb(string $title, string $route = '', array $params = []): BreadCrumbs
    {
        if ('' !== $route) {
            if (strpos($route, '.php') !== false)
                $this->crumbs['crumbs'][$title] = UrlGeneratorHelper::getPath('legacy', array_merge(['q' => $this->getBaseURL() . $route], $params));
            else {
                if (false === strpos($route, '__'))
                    $route = $this->getModule() . '__' . $route;
                $this->crumbs['crumbs'][$title] = UrlGeneratorHelper::getPath($route, $params);
            }
        }

        $this->crumbs['title'] = $title;

        return $this;
    }

    /**
     * getCrumbs
     * @return ArrayCollection
     */
    public function getCrumbs(): ArrayCollection
    {
        return new ArrayCollection(isset($this->crumbs['crumbs']) ? $this->crumbs['crumbs'] : []);
    }

    /**
     * @var string|null
     */
    private $module;

    /**
     * @return string|null
     */
    public function getModule(): ?string
    {
        return $this->module;
    }

    /**
     * Module.
     *
     * @param string|null $module
     * @return BreadCrumbs
     */
    public function setModule(?string $module): BreadCrumbs
    {
        if (0 === strpos($module, 'index.php?q='))
        {
            $module = explode('/', trim($module, '/'));
            $module = array_pop($module);
        }

        $this->module = strtolower(trim($module, '/'));

        return $this;
    }

    /**
     * @return bool
     */
    public function isLegacy(): bool
    {
        return $this->legacy;
    }

    /**
     * Legacy.
     *
     * @param bool $legacy
     * @return BreadCrumbs
     */
    public function setLegacy(bool $legacy): BreadCrumbs
    {
        $this->legacy = $legacy;
        return $this;
    }
}