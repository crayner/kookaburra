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
     * @return BreadCrumbItem[]|ArrayCollection
     */
    public function getItems(): ArrayCollection
    {
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
    public function setBaseURL($baseURL)
    {
        $this->baseURL = $baseURL;
        return $this;
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
        $this->setItems(null);
        $item = new BreadCrumbItem();
        $item->setName('Home')->setUri('');
        $this->addItem($item);
        $this->setTitle($module['title']);
        $this->setBaseURL($module['baseURL']);

        foreach($module['crumbs'] as $crumb) {
            $crumb['uri'] =  $this->getBaseURL() . $crumb['uri'];
            $item = new BreadCrumbItem($crumb);
            $this->addItem($item);
        }

        $item = new BreadCrumbItem();
        $item->setName($module['title'])->setUri(null);
        $this->addItem($item);

        return $this->getItems();
    }
}