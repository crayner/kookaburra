<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 20/09/2019
 * Time: 09:41
 */

namespace App\Form\Entity;

/**
 * Class ImportRun
 * @package App\Form\Entity
 */
class ImportRun
{
    /**
     * @var string
     */
    private $modes = 'sync';

    /**
     * @var string
     */
    private $columnOrder = 'guess';

    /**
     * @var string
     */
    private $file;

    /**
     * @var string
     */
    private $fieldDelimiter = ',';

    /**
     * @var string
     */
    private $stringEnclosure = '"';

    /**
     * @return string
     */
    public function getModes(): string
    {
        return $this->modes;
    }

    /**
     * Modes.
     *
     * @param string $modes
     * @return ImportRun
     */
    public function setModes(string $modes): ImportRun
    {
        $this->modes = $modes;
        return $this;
    }

    /**
     * @return string
     */
    public function getColumnOrder(): string
    {
        return $this->columnOrder;
    }

    /**
     * ColumnOrder.
     *
     * @param string $columnOrder
     * @return ImportRun
     */
    public function setColumnOrder(string $columnOrder): ImportRun
    {
        $this->columnOrder = $columnOrder;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getFile(): ?string
    {
        return $this->file;
    }

    /**
     * File.
     *
     * @param string $file
     * @return ImportRun
     */
    public function setFile(string $file): ImportRun
    {
        $this->file = $file;
        return $this;
    }

    /**
     * @return string
     */
    public function getFieldDelimiter(): string
    {
        return $this->fieldDelimiter;
    }

    /**
     * FieldDelimiter.
     *
     * @param string $fieldDelimiter
     * @return ImportRun
     */
    public function setFieldDelimiter(string $fieldDelimiter): ImportRun
    {
        $this->fieldDelimiter = $fieldDelimiter;
        return $this;
    }

    /**
     * @return string
     */
    public function getStringEnclosure(): string
    {
        return $this->stringEnclosure;
    }

    /**
     * StringEnclosure.
     *
     * @param string $stringEnclosure
     * @return ImportRun
     */
    public function setStringEnclosure(string $stringEnclosure): ImportRun
    {
        $this->stringEnclosure = $stringEnclosure;
        return $this;
    }
}