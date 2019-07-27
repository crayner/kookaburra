<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 27/07/2019
 * Time: 11:52
 */

namespace App\Twig;


class TableColumn
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $label;

    /**
     * @var string
     */
    private $headClass = 'column';

    /**
     * @var string
     */
    private $bodyClass = 'p-2 sm:p-3';

    /**
     * @var integer
     */
    private $colspan = 1;

    /**
     * @var string|null
     */
    private $style = '';

    /**
     * create
     * @param string $name
     * @param string $label
     */
    public static function create(string $name, string $label)
    {
        $column = new self();
        return $column->setName($name)->setLabel($label);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Name.
     *
     * @param string $name
     * @return TableColumn
     */
    public function setName(string $name): TableColumn
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * Label.
     *
     * @param string $label
     * @return TableColumn
     */
    public function setLabel(string $label): TableColumn
    {
        $this->label = $label;
        return $this;
    }

    /**
     * @return string
     */
    public function getHeadClass(): string
    {
        return $this->headClass;
    }

    /**
     * HeadClass.
     *
     * @param string $headClass
     * @return TableColumn
     */
    public function setHeadClass(string $headClass): TableColumn
    {
        $this->headClass = $headClass;
        return $this;
    }

    /**
     * @return string
     */
    public function getBodyClass(): string
    {
        return $this->bodyClass;
    }

    /**
     * BodyClass.
     *
     * @param string $bodyClass
     * @return TableColumn
     */
    public function setBodyClass(string $bodyClass): TableColumn
    {
        $this->bodyClass = $bodyClass;
        return $this;
    }

    /**
     * @return int
     */
    public function getColspan(): int
    {
        return $this->colspan;
    }

    /**
     * Colspan.
     *
     * @param int $colspan
     * @return TableColumn
     */
    public function setColspan(int $colspan): TableColumn
    {
        $this->colspan = $colspan;
        return $this;
    }

    /**
     * @return string
     */
    public function getStyle(): string
    {
        return $this->style ?: '';
    }

    /**
     * Style.
     *
     * @param string|null $style
     * @return TableColumn
     */
    public function setStyle(?string $style): TableColumn
    {
        $this->style = $style;
        return $this;
    }
}