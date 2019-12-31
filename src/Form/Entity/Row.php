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
 * Date: 14/08/2019
 * Time: 07:43
 */

namespace App\Form\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use ProxyManager\Generator\Util\UniqueIdentifierGenerator;

/**
 * Class Row
 * @package App\Form\Entity
 */
class Row
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var Collection|Column[]|null
     */
    private $columns;

    /**
     * @var string
     */
    private $class = '';

    /**
     * @var array
     */
    private $style = [];

    /**
     * Row constructor.
     */
    public function __construct(string $type = 'table')
    {
        $this->setClass('flex flex-col sm:flex-row justify-between content-center p-0');
        if ('table' !== $type)
            $this->setClass('flex p-0');  // @todo Realistic div default class.
        $this->setId();
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Id.
     *
     * @param string $id
     * @return Row
     */
    public function setId(): Row
    {
        $this->id = UniqueIdentifierGenerator::getIdentifier('row');
        return $this;
    }

    /**
     * @return Collection|Column[]|null
     */
    public function getColumns()
    {
        if (null === $this->columns)
            $this->columns = new ArrayCollection();
        return $this->columns;
    }

    /**
     * Columns.
     *
     * @param Collection|Column[]|null $columns
     * @return Row
     */
    public function setColumns($columns): Row
    {
        $this->columns = $columns;
        return $this;
    }

    /**
     * addColumn
     * @param Column $column
     * @return Row
     */
    public function addColumn(Column $column): Row
    {
        if ($this->getColumns()->containskey($column->getId()))
            return $this;

        $this->columns->set($column->getId(), $column);

        return $this;
    }

    /**
     * @return string
     */
    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * Class.
     *
     * @param string $class
     * @return Row
     */
    public function setClass(string $class): Row
    {
        $this->class = trim($class);
        return $this;
    }

    /**
     * @return array
     */
    public function getStyle(): array
    {
        return $this->style;
    }

    /**
     * Style.
     *
     * @param array $style
     * @return Row
     */
    public function setStyle(array $style): Row
    {
        $this->style = $style;
        return $this;
    }

    /**
     * mergeClass
     * @param string $class
     * @return Row
     */
    public function mergeClass(string $class): Row
    {
        if ('' === $class)
            return $this;
        $class .= ' '. $this->getClass();

        return $this->setClass($class);
    }

    /**
     * toArray
     * @return array
     */
    public function toArray(): array
    {
        $row['columns'] = [];
        $row['style'] = $this->getStyle();
        $row['class'] = $this->getClass();
        $row['id'] = $this->getId();
        foreach($this->getColumns() as $column)
            $row['columns'][] = $column->toArray();
        return $row;
    }
}