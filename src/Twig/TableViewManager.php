<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 27/07/2019
 * Time: 11:43
 */

namespace App\Twig;


use Doctrine\Common\Collections\ArrayCollection;

class TableViewManager
{
    /**
     * @var string
     */
    private $tableClass = 'w-full';

    /**
     * @var integer
     */
    private $cellSpacing = 0;

    /**
     * @var string|null
     */
    private $tableID;

    /**
     * @var string|null
     */
    private $theadClass = 'head text-xs';

    /**
     * @var string|null
     */
    private $tbodyClass;

    /**
     * @var ArrayCollection|TableColumn[]
     */
    private $columns;

    /**
     * @var string
     */
    private $wrapperClass = 'overflow-x-auto overflow-y-visible';

    /**
     * @var array
     */
    private $parameters;

    /**
     * TableViewManager constructor.
     * @param array $parameters
     */
    public function __construct(array $parameters = [])
    {
        $this->parameters = $parameters;
    }

    /**
     * @return string
     */
    public function getTableClass(): string
    {
        return $this->tableClass;
    }

    /**
     * TableClass.
     *
     * @param string $tableClass
     * @return TableViewManager
     */
    public function setTableClass(string $tableClass): TableViewManager
    {
        $this->tableClass = $tableClass;
        return $this;
    }

    /**
     * @return int
     */
    public function getCellSpacing(): int
    {
        return $this->cellSpacing;
    }

    /**
     * CellSpacing.
     *
     * @param int $cellsSpacing
     * @return TableViewManager
     */
    public function setCellSpacing(int $cellSpacing): TableViewManager
    {
        $this->cellSpacing = $cellSpacing;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getTableID(): ?string
    {
        return $this->tableID;
    }

    /**
     * TableID.
     *
     * @param string|null $tableID
     * @return TableViewManager
     */
    public function setTableID(?string $tableID): TableViewManager
    {
        $this->tableID = $tableID;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getTheadClass(): ?string
    {
        return $this->theadClass;
    }

    /**
     * TheadClass.
     *
     * @param string|null $theadClass
     * @return TableViewManager
     */
    public function setTheadClass(?string $theadClass): TableViewManager
    {
        $this->theadClass = $theadClass;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getTbodyClass(): ?string
    {
        return $this->tbodyClass;
    }

    /**
     * TbodyClass.
     *
     * @param string|null $tbodyClass
     * @return TableViewManager
     */
    public function setTbodyClass(?string $tbodyClass): TableViewManager
    {
        $this->tbodyClass = $tbodyClass;
        return $this;
    }

    /**
     * @return TableColumn[]|ArrayCollection
     */
    public function getColumns(): ArrayCollection
    {
        if (null === $this->columns)
            $this->columns = new ArrayCollection();

        return $this->columns;
    }

    /**
     * Columns.
     *
     * @param TableColumn[]|ArrayCollection|null $columns
     * @return TableViewManager
     */
    public function setColumns(?ArrayCollection $columns)
    {
        $this->columns = $columns;
        return $this;
    }

    /**
     * getColumn
     * @param string $name
     * @return TableColumn|null
     */
    public function getColumn(string $name): ?TableColumn
    {
        if ($this->getColumns()->containsKey($name))
            return $this->columns->get($name);
        return null;
    }

    /**
     * addColumn
     * @param string $name
     * @param string $label
     * @return TableColumn
     */
    public function addColumn(string $name, string $label): TableColumn
    {
        if (!$this->getColumns()->containsKey($name))
        {
            $column = TableColumn::create($name, $label);
            $this->columns->set($name, $column);
            return $column;
        }

        $column = $this->columns->get($name);
        $column->setLabel($label);
        return $column;
    }

    /**
     * @return string
     */
    public function getWrapperClass(): string
    {
        return $this->wrapperClass;
    }

    /**
     * WrapperClass.
     *
     * @param string $wrapperClass
     * @return TableViewManager
     */
    public function setWrapperClass(string $wrapperClass): TableViewManager
    {
        $this->wrapperClass = $wrapperClass;
        return $this;
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * Parameters.
     *
     * @param array $parameters
     * @return TableViewManager
     */
    public function setParameters(array $parameters): TableViewManager
    {
        $this->parameters = $parameters;
        return $this;
    }
}