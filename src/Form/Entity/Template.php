<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 14/08/2019
 * Time: 07:42
 */

namespace App\Form\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Class Template
 * @package App\Form\Entity
 */
class Template
{
    /**
     * @var string
     */
    private $type = 'table';

    /**
     * @var array
     */
    private static $typeList = [
        'table',
        'div',
    ];

    /**
     * @var Collection|Row[]|null
     */
    private $rows;

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type = in_array($this->type, self::getTypeList()) ? $this->type : 'table';
    }

    /**
     * Type.
     *
     * @param string $type
     * @return Template
     */
    public function setType(string $type): Template
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return array
     */
    public static function getTypeList(): array
    {
        return self::$typeList;
    }

    /**
     * @return Collection|Row[]|null
     */
    public function getRows()
    {
        if (null === $this->rows)
            $this->rows = new ArrayCollection();
        return $this->rows;
    }

    /**
     * Rows.
     *
     * @param Collection|Row[]|null $rows
     * @return Template
     */
    public function setRows(Collection $rows): Template
    {
        $this->rows = $rows;
        return $this;
    }

    /**
     * addRow
     * @param Row $row
     * @return Template
     */
    public function addRow(Row $row): Template
    {
        if ($this->getRows()->containskey($row->getId()))
            return $this;

        $this->rows->set($row->getId(), $row);

        return $this;
    }

    /**
     * toArray
     * @return array
     */
    public function toArray(): array
    {
        $template['type'] = $this->getType();
        $template['rows'] = [];
        $template['buttons'] = false;
        foreach ($this->getRows() as $row)
            $template['rows'][] = $row->toArray();
        return $template;
    }
}