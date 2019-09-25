<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 22/09/2019
 * Time: 09:53
 */

namespace App\Form\Entity;


class ImportColumn
{
    /**
     * @var integer
     */
    private $order;

    /**
     * @var null|string
     */
    private $text;

    /**
     * @var array
     */
    private $fieldType;

    /**
     * @var string|null
     */
    private $name;

    /**
     * @var string|null
     */
    private $label;

    /**
     * @var array
     */
    private $columnChoices;

    /**
     * @var array
     */
    private $flags;

    /**
     * @return int
     */
    public function getOrder(): int
    {
        return $this->order = intval($this->order) ?: 0;
    }

    /**
     * Order.
     *
     * @param int|null $order
     * @return ImportColumn
     */
    public function setOrder($order): ImportColumn
    {
        $this->order = intval($order);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getText(): ?string
    {
        return $this->text;
    }

    /**
     * Text.
     *
     * @param string|null $text
     * @return ImportColumn
     */
    public function setText(?string $text): ImportColumn
    {
        $this->text = $text;
        return $this;
    }

    /**
     * @return array
     */
    public function getFieldType(): array
    {
        return $this->fieldType;
    }

    /**
     * FieldType.
     *
     * @param array $fieldType
     * @return ImportColumn
     */
    public function setFieldType(array $fieldType): ImportColumn
    {
        $this->fieldType = $fieldType;
        return $this;
    }

    /**
     * @return null|array
     */
    public function getColumnChoices(): ?array
    {
        return $this->columnChoices;
    }

    /**
     * setColumnChoices
     * @param array $choices1
     * @param array $choices2
     * @return ImportColumn
     */
    public function setColumnChoices(array $choices1, array $choices2): ImportColumn
    {
        $columnChoices = [];
        $choices = $choices1 + $choices2;
        foreach($choices as $value=>$prompt)
            $columnChoices[intval($value)] = $prompt;

        $this->columnChoices = $columnChoices;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Name.
     *
     * @param string|null $name
     * @return ImportColumn
     */
    public function setName(?string $name): ImportColumn
    {
        $this->name = $name;
        return $this;
    }

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
     * @return ImportColumn
     */
    public function setLabel(?string $label): ImportColumn
    {
        $this->label = $label;
        return $this;
    }

    /**
     * getFlags
     * @return array
     */
    public function getFlags(): array
    {
        return $this->flags = $this->flags ?: [];
    }

    /**
     * setFlags
     * @param array $flags
     * @return ImportColumn
     */
    public function setFlags(array $flags): ImportColumn
    {
        $w = [];
        foreach($flags as $flag)
            $w[$flag] = true;
        $this->flags = $w;
        return $this;
    }
}