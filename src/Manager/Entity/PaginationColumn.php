<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 14/09/2019
 * Time: 11:44
 */

namespace App\Manager\Entity;

/**
 * Class PaginationColumn
 * @package App\Manager\Entity
 */
class PaginationColumn
{
    /**
     * @var string|null
     */
    private $label;

    /**
     * @var string|null
     */
    private $help;

    /**
     * @var string|null
     */
    private $contentKey;

    /**
     * @var bool
     */
    private $sort = false;

    /**
     * @var string
     */
    private $class = '';

    /**
     * @return string|null
     */
    public function getLabel(): ?string
    {
        return $this->label;
    }

    /**
     * Label.
     *
     * @param string|null $label
     * @return PaginationColumn
     */
    public function setLabel(?string $label): PaginationColumn
    {
        $this->label = $label;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getHelp(): ?string
    {
        return $this->help;
    }

    /**
     * Help.
     *
     * @param string|null $help
     * @return PaginationColumn
     */
    public function setHelp(?string $help): PaginationColumn
    {
        $this->help = $help;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getContentKey(): ?string
    {
        return $this->contentKey;
    }

    /**
     * ContentKey.
     *
     * @param string|null $contentKey
     * @return PaginationColumn
     */
    public function setContentKey(?string $contentKey): PaginationColumn
    {
        $this->contentKey = $contentKey;
        return $this;
    }

    /**
     * @return bool
     */
    public function isSort(): bool
    {
        return $this->sort;
    }

    /**
     * Sort.
     *
     * @param bool $sort
     * @return PaginationColumn
     */
    public function setSort(bool $sort): PaginationColumn
    {
        $this->sort = $sort;
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
     * @return PaginationColumn
     */
    public function setClass(string $class): PaginationColumn
    {
        $this->class = $class;
        return $this;
    }

    /**
     * toArray
     * @return array
     */
    public function toArray(): array
    {
        $result = (array) $this;
        $x = [];
        foreach($result as $q=>$w)
            $x[str_replace("\x00App\Manager\Entity\PaginationColumn\x00", '', $q)] = $w;

        return $x;
    }
}