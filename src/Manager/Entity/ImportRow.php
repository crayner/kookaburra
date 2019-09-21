<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 21/09/2019
 * Time: 09:58
 */

namespace App\Manager\Entity;


class ImportRow
{
    /**
     * @var int
     */
    private $count;

    /**
     * @var int|string
     */
    private $order;

    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $fieldType;

    /**
     * @var string|null
     */
    private $example;

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
    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * Count.
     *
     * @param int $count
     * @return ImportRow
     */
    public function setCount(int $count): ImportRow
    {
        $this->count = $count;
        return $this;
    }

    /**
     * @return int|string
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Order.
     *
     * @param int|string $order
     * @return ImportRow
     */
    public function setOrder($order)
    {
        $this->order = $order;
        return $this;
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
     * @return ImportRow
     */
    public function setName(string $name): ImportRow
    {
        $this->name = $name;
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
     * @return ImportRow
     */
    public function setFieldType(array $fieldType): ImportRow
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
     * @return ImportRow
     */
    public function setColumnChoices(array $choices1, array $choices2): ImportRow
    {
        $columnChoices = [];
        $columnChoices = $choices1 + $choices2;

        $this->columnChoices = $columnChoices;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getExample(): ?string
    {
        return $this->example;
    }

    /**
     * Example.
     *
     * @param string|null $example
     * @return ImportRow
     */
    public function setExample(?string $example): ImportRow
    {
        $this->example = $example;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCsvData(): ?string
    {
        return $this->csvData;
    }

    /**
     * CsvData.
     *
     * @param string|null $csvData
     * @return ImportRow
     */
    public function setCsvData(?string $csvData): ImportRow
    {
        $this->csvData = $csvData;
        return $this;
    }

    /**
     * @return array
     */
    public function getFlags(): array
    {
        return $this->flags;
    }

    /**
     * Flags.
     *
     * @param array $flags
     * @return ImportRow
     */
    public function setFlags(array $flags): ImportRow
    {
        $w = [];
        foreach($flags as $flag)
            $w[$flag] = true;
        $this->flags = $w;
        return $this;
    }
}