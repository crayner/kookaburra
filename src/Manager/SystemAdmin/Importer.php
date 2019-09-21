<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 20/09/2019
 * Time: 13:48
 */

namespace App\Manager\SystemAdmin;


use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use Symfony\Component\HttpFoundation\File\File;

class Importer
{
    const COLUMN_DATA_SKIP = -1;
    const COLUMN_DATA_CUSTOM = -2;
    const COLUMN_DATA_FUNCTION = -3;
    const COLUMN_DATA_LINKED = -4;
    const COLUMN_DATA_HIDDEN = -5;

    const ERROR_IMPORT_FILE = 200;
    const ERROR_REQUIRED_FIELD_MISSING = 205;
    const ERROR_INVALID_FIELD_VALUE = 206;
    const ERROR_DATABASE_GENERIC = 208;
    const ERROR_DATABASE_FAILED_INSERT = 209;
    const ERROR_DATABASE_FAILED_UPDATE = 210;
    const ERROR_NON_UNIQUE_KEY =212;
    const ERROR_RELATIONAL_FIELD_MISMATCH = 213;
    const ERROR_INVALID_HAS_SPACES = 214;

    const WARNING_DUPLICATE_KEY = 101;
    const WARNING_RECORD_NOT_FOUND = 102;

    /**
     * @var int
     */
    private $errorID = 0;

    /**
     * @var string
     */
    private $fieldDelimiter = ',';

    /**
     * @var string
     */
    private $stringEnclosure = '"';

    /**
     * @var array
     */
    private $headerRow = [];

    /**
     * @var array
     */
    private $firstRow = [];

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
     * @return Importer
     */
    public function setFieldDelimiter(string $fieldDelimiter): Importer
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
     * @return Importer
     */
    public function setStringEnclosure(string $stringEnclosure): Importer
    {
        $this->stringEnclosure = $stringEnclosure;
        return $this;
    }

    /**
     * @return int
     */
    public function getErrorID(): int
    {
        return $this->errorID;
    }

    /**
     * ErrorID.
     *
     * @param int $errorID
     * @return Importer
     */
    public function setErrorID(int $errorID): bool
    {
        $this->errorID = $errorID;
        return false;
    }

    /**
     * @return array
     */
    public function getHeaderRow(): array
    {
        return $this->headerRow;
    }

    /**
     * HeaderRow.
     *
     * @param array $headerRow
     * @return Importer
     */
    public function setHeaderRow(array $headerRow): Importer
    {
        $this->headerRow = $headerRow;
        return $this;
    }

    /**
     * @return array
     */
    public function getFirstRow(): array
    {
        return $this->firstRow;
    }

    /**
     * FirstRow.
     *
     * @param array $firstRow
     * @return Importer
     */
    public function setFirstRow(array $firstRow): Importer
    {
        $this->firstRow = $firstRow;
        return $this;
    }

    /**
     * readFileIntoCSV
     * @return bool|string|string[]|null
     */
    public function readFileIntoCSV(File $file)
    {
        $data = '';

        if ($file->guessExtension() === 'csv') {
            dd($this);
            $opts = array('http' => array('header' => "Accept-Charset: utf-8;q=0.7,*;q=0.7\r\n"."Content-Type: text/html; charset =utf-8\r\n"));
            $context = stream_context_create($opts);

            $data = file_get_contents($_FILES['file']['tmp_name'], false, $context);
            if (mb_check_encoding($data, 'UTF-8') == false) {
                $data = mb_convert_encoding($data, 'UTF-8');
            }

            // Grab the header & first row for Step 1
            if ($this->openCSVFile($_FILES['file']['tmp_name'])) {
                $this->headerRow = $this->getCSVLine();
                $this->firstRow = $this->getCSVLine();
                $this->closeCSVFile();
            } else {
                $this->errorID = Importer::ERROR_IMPORT_FILE;
                return false;
            }
        } elseif (in_array($file->guessExtension(), ['xlsx','xls','xml','ods'])) {
            // Try to use the best reader if available, otherwise catch any read errors
            try {
                if ($file->guessExtension() === 'xml') {
                    $objReader = IOFactory::load($file->getRealPath());
                    $objPHPExcel = $objReader->load($file->getRealPath());
                } else {
                    $objPHPExcel = IOFactory::load($file->getRealPath());
                }
            } catch (Exception $e) {
                return $this->setErrorID(Importer::ERROR_IMPORT_FILE);
            }

            $objWorksheet = $objPHPExcel->getActiveSheet();
            $lastColumn = $objWorksheet->getHighestColumn();

            // Grab the header & first row for Step 1
            foreach ($objWorksheet->getRowIterator(0, 2) as $rowIndex => $row) {
                $array = $objWorksheet->rangeToArray('A'.$rowIndex.':'.$lastColumn.$rowIndex, null, true, true, false);

                if ($rowIndex == 1) {
                    $this->setHeaderRow($array[0]);
                } elseif ($rowIndex == 2) {
                    $this->setFirstRow($array[0]);
                }
            }

            $objWriter = IOFactory::createWriter($objPHPExcel, 'Csv');

            // Export back to CSV
            ob_start();
            $objWriter->save('php://output');
            $data = ob_get_clean();
        }

        return $data;
    }
}