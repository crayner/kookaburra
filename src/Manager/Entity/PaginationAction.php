<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 14/09/2019
 * Time: 12:12
 */

namespace App\Manager\Entity;

/**
 * Class PaginationAction
 * @package App\Manager\Entity
 */
class PaginationAction
{
    /**
     * @var string
     */
    private $route;

    /**
     * @var array
     */
    private $route_params = [];

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $aClass;

    /**
     * @var string
     */
    private $spanClass;

    /**
     * @var string
     */
    private $columnClass = '';

    /**
     * @return string
     */
    public function getRoute(): string
    {
        return $this->route;
    }

    /**
     * Route.
     *
     * @param string $route
     * @return PaginationAction
     */
    public function setRoute(string $route): PaginationAction
    {
        $this->route = $route;
        return $this;
    }

    /**
     * @return array
     */
    public function getRouteParams(): array
    {
        return $this->route_params;
    }

    /**
     * RouteParams.
     *
     * @param array $route_params
     * @return PaginationAction
     */
    public function setRouteParams(array $route_params): PaginationAction
    {
        $this->route_params = $route_params;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Title.
     *
     * @param string $title
     * @return PaginationAction
     */
    public function setTitle(string $title): PaginationAction
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getAClass(): string
    {
        return $this->aClass;
    }

    /**
     * AClass.
     *
     * @param string $aClass
     * @return PaginationAction
     */
    public function setAClass(string $aClass): PaginationAction
    {
        $this->aClass = $aClass;
        return $this;
    }

    /**
     * @return string
     */
    public function getSpanClass(): string
    {
        return $this->spanClass;
    }

    /**
     * SpanClass.
     *
     * @param string $spanClass
     * @return PaginationAction
     */
    public function setSpanClass(string $spanClass): PaginationAction
    {
        $this->spanClass = $spanClass;
        return $this;
    }

    public function toArray() {
       return [
           'spanClass' => $this->getSpanClass(),
           'aClass' => $this->getAClass(),
           'title' => $this->getTitle(),
           'columnClass' => $this->getColumnClass(),
       ];
    }

    /**
     * @return string
     */
    public function getColumnClass(): string
    {
        return $this->columnClass;
    }

    /**
     * ColumnClass.
     *
     * @param string $columnClass
     * @return PaginationAction
     */
    public function setColumnClass(string $columnClass): PaginationAction
    {
        $this->columnClass = $columnClass;
        return $this;
    }
}