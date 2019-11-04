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
     * @var string|array|null
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
     * @var string
     */
    private $contentType = 'standard';

    /**
     * @var array
     */
    private $options = [];

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
     * @return string|array|null
     */
    public function getContentKey()
    {
        return $this->contentKey;
    }

    /**
     * ContentKey.
     *
     * @param string|array|null $contentKey
     * @return PaginationColumn
     */
    public function setContentKey($contentKey): PaginationColumn
    {
        $this->contentKey = is_string($contentKey) ? [$contentKey] : $contentKey ;
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
     * @return string
     */
    public function getContentType(): string
    {
        return $this->contentType;
    }

    /**
     * ContentType.
     *
     * @param string $contentType
     * @return PaginationColumn
     */
    public function setContentType(string $contentType): PaginationColumn
    {
        $this->contentType = in_array($contentType, ['image','standard']) ? $contentType : 'standard';
        return $this;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * Options.
     *
     * @param array $options
     * @return PaginationColumn
     */
    public function setOptions(array $options): PaginationColumn
    {
        $this->options = $options;
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