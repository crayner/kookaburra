<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 12/12/2019
 * Time: 12:47
 */

namespace App\Manager\Entity;
use App\Util\TranslationsHelper;

/**
 * Class PaginationFilter
 * @package App\Manager\Entity
 */
class PaginationFilter
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
     * @var mixed
     */
    private $value;

    /**
     * @var string
     */
    private $contentKey;

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
     * @return PaginationFilter
     */
    public function setName(string $name): PaginationFilter
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
     * @return PaginationFilter
     */
    public function setLabel(string $label): PaginationFilter
    {
        $this->label = $label;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Value.
     *
     * @param mixed $value
     * @return PaginationFilter
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getContentKey(): string
    {
        return $this->contentKey;
    }

    /**
     * ContentKey.
     *
     * @param string $contentKey
     * @return PaginationFilter
     */
    public function setContentKey(string $contentKey): PaginationFilter
    {
        $this->contentKey = $contentKey;
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
            $x[str_replace("\x00App\Manager\Entity\PaginationFilter\x00", '', $q)] = $w;
        $x['label'] = TranslationsHelper::translate($x['label'] ?: $x['name']);
        return $x;
    }
}