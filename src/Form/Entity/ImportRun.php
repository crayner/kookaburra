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

use App\Manager\Entity\ImportRow;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;

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
     * @var File
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
     * @var bool
     */
    private $ignoreErrors = false;

    /**
     * @var bool
     */
    private $syncField = false;

    /**
     * @var null|string
     */
    private $syncColumn;

    /**
     * @var Collection
     */
    private $columnCollection;

    /**
     * @var null|string
     */
    private $csvData;

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
     * @return null|File
     */
    public function getFile(): ?File
    {
        return $this->file;
    }

    /**
     * File.
     *
     * @param null|File $file
     * @return ImportRun
     */
    public function setFile(?File $file): ImportRun
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

    /**
     * @return bool
     */
    public function isIgnoreErrors(): bool
    {
        return $this->ignoreErrors = $this->ignoreErrors ? true : false;
    }

    /**
     * IgnoreErrors.
     *
     * @param bool $ignoreErrors
     * @return ImportRun
     */
    public function setIgnoreErrors(bool $ignoreErrors): ImportRun
    {
        $this->ignoreErrors = $ignoreErrors ? true : false;
        return $this;
    }

    /**
     * @return bool
     */
    public function isSyncField(): bool
    {
        return $this->syncField;
    }

    /**
     * SyncField.
     *
     * @param bool $syncField
     * @return ImportRun
     */
    public function setSyncField(bool $syncField): ImportRun
    {
        $this->syncField = $syncField;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSyncColumn(): ?string
    {
        return $this->syncColumn;
    }

    /**
     * SyncColumn.
     *
     * @param string|null $syncColumn
     * @return ImportRun
     */
    public function setSyncColumn(?string $syncColumn): ImportRun
    {
        $this->syncColumn = $syncColumn;
        return $this;
    }

    /**
     * getColumnCollection
     * @return Collection
     */
    public function getColumnCollection(): Collection
    {
        return $this->columnCollection = $this->columnCollection ?: new ArrayCollection();
    }

    /**
     * ColumnCollection.
     *
     * @param Collection $columnCollection
     * @return ImportRun
     */
    public function setColumnCollection(Collection $columnCollection): ImportRun
    {
        $this->columnCollection = $columnCollection;
        return $this;
    }

    /**
     * addColumnRow
     * @param ImportRow $row
     * @return ImportRun
     */
    public function addColumnRow(ImportRow $row): ImportRun
    {
        $this->getColumnCollection()->add($row);
        return $this;
    }

    /**
     * getCsvData
     * @return string|null
     */
    public function getCsvData(): ?string
    {
        return $this->csvData;
    }

    /**
     * setCsvData
     * @param string|null $csvData
     * @return ImportRun
     */
    public function setCsvData(?string $csvData): ImportRun
    {
        $this->csvData = $csvData;
        return $this;
    }
}