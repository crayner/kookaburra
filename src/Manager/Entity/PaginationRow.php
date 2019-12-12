<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 14/09/2019
 * Time: 11:43
 */

namespace App\Manager\Entity;

use App\Util\TranslationsHelper;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Class PaginationRow
 * @package App\Manager\Entity
 */
class PaginationRow
{
    /**
     * @var Collection|PaginationColumn[]
     */
    private $columns;

    /**
     * @var Collection|PaginationAction[]
     */
    private $actions;

    /**
     * @var Collection|PaginationFilter[]
     */
    private $filters;

    /**
     * @return PaginationColumn[]|Collection
     */
    public function getColumns(): ArrayCollection
    {
        return $this->columns = $this->columns ?: new ArrayCollection();
    }

    /**
     * Columns.
     *
     * @param PaginationColumn[]|Collection $columns
     * @return PaginationRow
     */
    public function setColumns($columns): PaginationRow
    {
        $this->columns = $columns;
        return $this;
    }

    /**
     * addColumn
     * @param PaginationColumn $column
     * @return PaginationRow
     */
    public function addColumn(PaginationColumn $column): PaginationRow
    {
        if (!$this->getColumns()->contains($column)) {
            $this->columns->add($column);
        }
        return $this;
    }

    /**
     * getActions
     * @return ArrayCollection
     */
    public function getActions(): ArrayCollection
    {
        return $this->actions = $this->actions ?: new ArrayCollection();
    }

    /**
     * Actions.
     *
     * @param ArrayCollection $actions
     * @return PaginationRow
     */
    public function setActions(ArrayCollection $actions): PaginationRow
    {
        $this->actions = $actions;
        return $this;
    }

    /**
     * Add Action.
     *
     * @param array $actions
     * @return PaginationRow
     */
    public function addAction(PaginationAction $action): PaginationRow
    {
        if (!$this->getActions()->contains($action))
            $this->actions->add($action);
        return $this;
    }

    /**
     * @return PaginationFilter[]|ArrayCollection
     */
    public function getFilters(): ArrayCollection
    {
        return $this->filters = $this->filters ?: new ArrayCollection();
    }

    /**
     * Filters.
     *
     * @param PaginationFilter[]|ArrayCollection $filters
     * @return PaginationRow
     */
    public function setFilters(ArrayCollection $filters): PaginationRow
    {
        $this->filters = $filters;
        return $this;
    }

    /**
     * Add Filter.
     *
     * @param array $actions
     * @return PaginationRow
     */
    public function addFilter(PaginationFilter $filter): PaginationRow
    {
        if (!$this->getFilters()->contains($filter))
            $this->filters->add($filter);
        return $this;
    }

    /**
     * toArray
     * @return array
     */
    public function toArray(): array
    {
        return [
            'columns' => $this->getColumns()->toArray(),
            'actions' => $this->getActions()->toArray(),
            'filters' => $this->getFilters()->toArray(),
            'actionTitle' => TranslationsHelper::translate('Actions'),
            'emptyContent' => TranslationsHelper::translate('There are no records to display.'),
            'caption' => TranslationsHelper::translate('Records {start}-{end} of {total}'),
            'firstPage' => TranslationsHelper::translate('First Page'),
            'prevPage' => TranslationsHelper::translate('Previous Page'),
            'nextPage' => TranslationsHelper::translate('Next Page'),
            'lastPage' => TranslationsHelper::translate('Last Page'),
        ];
    }
}