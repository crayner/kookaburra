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

use App\Form\Entity\ImportControl;
use App\Manager\Entity\SystemAdmin\ImportReport;
use App\Provider\ProviderFactory;
use App\Util\TranslationsHelper;
use Doctrine\DBAL\Driver\PDOException;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class Importer
 * @package App\Manager\SystemAdmin
 */
class Importer
{
    const COLUMN_DATA_SKIP = -1;
    const COLUMN_DATA_CUSTOM = -2;
    const COLUMN_DATA_FUNCTION = -3;
    const COLUMN_DATA_LINKED = -4;
    const COLUMN_DATA_HIDDEN = -5;

    const ERROR_IMPORT_FILE = 'There was an error reading the file {value}.';
    const ERROR_REQUIRED_FIELD_MISSING = 205;
    const ERROR_INVALID_FIELD_VALUE = 206;
    const ERROR_DATABASE_GENERIC = 208;
    const ERROR_DATABASE_FAILED_INSERT = 209;
    const ERROR_DATABASE_FAILED_UPDATE = 210;
    const ERROR_NON_UNIQUE_KEY =212;
    const ERROR_RELATIONAL_FIELD_MISMATCH = 213;
    const ERROR_INVALID_HAS_SPACES = 214;

    const WARNING_DUPLICATE = 'A duplicate entry already exists for this record. Record skipped.';
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
     * @var ImportReport
     */
    private $report;

    /**
     * @var ImportControl
     */
    private $importControl;

    /**
     * @var array|null
     */
    private $importData;

    /**
     * @var ConstraintViolationList
     */
    private $violations;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var bool
     */
    private $importSuccess = false;

    /**
     * @var bool
     */
    private $buildSuccess = true;

    /**
     * @var bool
     */
    private $databaseSuccess = true;

    /**
     * @var int
     */
    private $processedRows = 0;

    /**
     * @var int
     */
    private $processedErrorRows = 0;

    /**
     * @var int
     */
    private $processedErrors = 0;

    /**
     * @var int
     */
    private $processedWarnings = 0;

    /**
     * @var int
     */
    private $inserts = 0;

    /**
     * @var int
     */
    private $inserts_skipped = 0;

    /**
     * @var int
     */
    private $updates = 0;

    /**
     * @var int
     */
    private $updates_skipped = 0;

    /**
     * Importer constructor.
     * @param Validation $validation
     */
    public function __construct(ValidatorInterface $validator, LoggerInterface $logger)
    {
        $this->validator = $validator;
        $this->logger = $logger;
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
        $extension = $file->guessExtension();
        if ($extension === null) {
            $extension = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
        }
        if ($extension === 'csv') {
            $opts = array('http' => array('header' => "Accept-Charset: utf-8;q=0.7,*;q=0.7\r\n"."Content-Type: text/html; charset =utf-8\r\n"));
            $context = stream_context_create($opts);

            $data = file_get_contents($file->getRealPath(), false, $context);
            if (mb_check_encoding($data, 'UTF-8') == false) {
                $data = mb_convert_encoding($data, 'UTF-8');
            }

            // Grab the header & first row for Step 1
            if ($csvData = $this->readCSVFile($file->getRealPath())) {
                $this->setHeaderFirstLine($csvData);
            } else {
                $this->errorID = Importer::ERROR_IMPORT_FILE;
                return false;
            }
        } elseif (in_array($extension, ['xlsx','xls','xml','ods'])) {
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

    /**
     * Read CSV File
     *
     * @param  string  Full File Path
     * @return  bool  true on success
     */
    public function readCSVFile($csvFile)
    {
        return file_get_contents($csvFile);
    }

    /**
     * setHeaderFirstLine
     * @param string $data
     * @return $this
     */
    public function setHeaderFirstLine(?string $data = null): Importer
    {
        $data = $this->readCSVString($data);

        $this->setHeaderRow(array_keys($data[0]));
        $this->setFirstRow(array_values($data[0]));
        return $this;
    }

    /**
     * readCSVString
     * @param string|null $data
     * @return array
     */
    public function readCSVString(?string $data = null): array
    {
        if ($data === null)
            $data = $this->getImportControl()->getCsvData();

        $serializer = new Serializer([new ObjectNormalizer()], [new CsvEncoder()]);
        return $this->importData = $serializer->decode($data, 'csv');

    }

    /**
     * @return ImportReport
     */
    public function getReport(): ImportReport
    {
        return $this->report;
    }

    /**
     * Report.
     *
     * @param ImportReport $report
     * @return Importer
     */
    public function setReport(ImportReport $report): Importer
    {
        $this->report = $report;
        return $this;
    }

    /**
     * @return ImportControl
     */
    public function getImportControl(): ImportControl
    {
        return $this->importControl;
    }

    /**
     * ImportControl.
     *
     * @param ImportControl $importControl
     * @return Importer
     */
    public function setImportControl(ImportControl $importControl): Importer
    {
        $this->importControl = $importControl;
        return $this;
    }

    /**
     * isIgnoreError
     * @return bool
     */
    public function isIgnoreError(): bool
    {
        return $this->getImportControl()->isIgnoreErrors();
    }

    /**
     * validateImport
     * @param bool $persist
     * @return bool
     */
    public function validateImport(bool $persist = false): bool
    {
        $table = '\App\Entity\\' . $this->getReport()->getDetail('table');

        $line = 1;
        if ($persist) {
            $em = ProviderFactory::getEntityManager();
            $em->beginTransaction();
            $this->getLogger()->notice(TranslationsHelper::translate('The import is attempting to write to the database for table "{table}"', ['{table}' => $table]));
        }
        foreach($this->readCSVString() as $data)
        {
            $columnID = 0;
            $entity = new $table();
            if (in_array($this->getImportControl()->getMode(), ['sync','update'])) {
                $syncColumn = 'id';
                if ($this->getImportControl()->isSyncField())
                    $syncColumn = $this->getImportControl()->getSyncColumn();
                $field = $this->getReport()->getFields()->get($syncColumn) ?: null;
                if (null === $field && $this->getImportControl()->getMode() === 'update') {
                    $this->incrementUpdatesSkipped()
                        ->incrementProcessedRows();
                    $this->getLogger()->warning(TranslationsHelper::translate('Missing value for a required field.'), ['line' => $line, 'cause' => $table, 'propertyPath' => $syncColumn]);
                    $line++;
                    continue;
                } else {
                    $value = null;
                    if (null !== $field)
                        $value = $data[$field->getLabel()] ?: null;
                    $search = [$syncColumn => $value];
                    $entity = ProviderFactory::getRepository($table)->findOneBy($search);
                }
                if (null === $entity && $this->getImportControl()->getMode() === 'update') {
                    $this->incrementUpdatesSkipped()
                        ->incrementProcessedRows();
                    $this->getLogger()->warning(TranslationsHelper::translate('A database entry for this record could not be found. Record skipped.'), ['line' => $line, 'cause' => $table, 'propertyPath' => $syncColumn, 'value' => $value]);
                    $line++;
                    continue;
                }

                if (null === $entity)
                    $entity = new $table();

            }
            $orderData = array_values($data);
            $rowError = false;

            foreach($data as $value)
            {
                $importColumn = $this->getImportControl()->getColumns()->get($columnID);

                $field = $this->getReport()->getFields()->get($importColumn->getName());

                $setName = 'set' . ucfirst($importColumn->getName());

                if ($field->isRelational())
                {
                    $relationship = $field->getRelationship();
                    $relTable = '\App\Entity\\' . $relationship['table'];
                    $relFind = 'findOneBy' . ucfirst($relationship['field']);
                    $orderData[$importColumn->getOrder()] = ProviderFactory::getRepository($relTable)->$relFind($value ?: null);
                }
                $entity->$setName($orderData[$importColumn->getOrder()]);
                $columnID++;
            }

            foreach($violations = $this->getValidator()->validate($entity) as $violation)
            {
                $message = $violation->getMessage();
                $level = 'error';
                if ($violation->getConstraint() instanceof UniqueEntity)
                {
                    $message = Importer::WARNING_DUPLICATE;
                    $level = 'warning';
                }

                $this->getViolations()->add($withLine = new ConstraintViolation(
                    $message,
                    $violation->getMessageTemplate(),
                    array_merge($violation->getParameters(), ['line' => $line, 'level' => $level]),
                    $violation->getRoot(),
                    $violation->getPropertyPath(),
                    $violation->getInvalidValue(),
                    $violation->getPlural(),
                    $violation->getCode(),
                    $violation->getConstraint(),
                    $violation->getCause()
                ));
                $this->addLogMessage($withLine);
                if ($level === 'error') {
                    $this->incrementProcessedErrors();
                    $rowError = true;
                } else
                    $this->incrementProcessedWarnings();
            }
            if ($rowError)
                $this->incrementProcessedErrorRows();
            $line++;
            $this->incrementProcessedRows();
            if ($entity->getId() > 0) {
                if ($violations->count() > 0)
                    $this->incrementUpdatesSkipped();
                else {
                    $this->incrementUpdates();
                    if ($persist) {
                        $em->persist($entity);
                        $this->getLogger()->notice(TranslationsHelper::translate('The importer updated a record "{id}" into the table "{table}"', ['{id}' => $entity->__toString(), '{table}' => get_class($entity)]), ['target' => $table, 'id' => $entity->__toString()]);
                    }
                }
            } else {
                if ($violations->count() > 0)
                    $this->incrementInsertsSkipped();
                else {
                    $this->incrementInserts();
                    if ($persist) {
                        $em->persist($entity);
                        $this->getLogger()->notice(TranslationsHelper::translate('The importer inserted a record "{id}" into the table "{table}"', ['{id}' => $entity->__toString(), '{table}' => get_class($entity)]), ['target' => $table, 'id' => $entity->__toString()]);
                    }
                }
            }
        }


        $this->setImportSuccess(true);

        if ($this->getInserts() + $this->getUpdates() > 0 && $persist)
        {
            try {
                $em->flush();
                $em->commit();
                $this->getLogger()->notice(TranslationsHelper::translate('importer_database_commit', ['count' => $this->getInserts() + $this->getUpdates()]), ['target' => $table]);
            } catch (PDOException $e) {
                $em->rollback();
                $this->setImportSuccess(false);
                $this->getLogger()->error(TranslationsHelper::translate('The database failed to import, and was rolled back.'), ['target' => $table]);
            }
        } elseif ($persist)
            $this->getLogger()->notice(TranslationsHelper::translate('importer_database_commit', ['count' => 0]), ['target' => $table]);


        return $this->isImportSuccess();
    }

    /**
     * getValidator
     * @return ValidatorInterface
     */
    public function getValidator(): ValidatorInterface
    {
        return $this->validator;
    }

    /**
     * @return ConstraintViolationList
     */
    public function getViolations(): ConstraintViolationList
    {
        return $this->violations = $this->violations ?: new ConstraintViolationList();
    }

    /**
     * setViolations
     * @param ConstraintViolationList $violations
     * @return Importer
     */
    public function setViolations(ConstraintViolationList $violations): Importer
    {
        $this->violations = $violations;
        return $this;
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }

    /**
     * Logger.
     *
     * @param LoggerInterface $logger
     * @return Importer
     */
    public function setLogger(LoggerInterface $logger): Importer
    {
        $this->logger = $logger;
        return $this;
    }

    /**
     * addLogMessage
     * @param ConstraintViolation $violation
     * @return Importer
     */
    private function addLogMessage(ConstraintViolation $violation): Importer
    {
        $level = $violation->getParameters()['level'] ?: 'error';
        if ($level === 'error')
            $this->setBuildSuccess(false);
        $this->getLogger()->$level(TranslationsHelper::translate($violation->getMessage(), $violation->getParameters()), [
            'line' => $violation->getParameters()['line'],
            'propertyPath' => $violation->getPropertyPath(),
            'invalidValue' => $violation->getInvalidValue(),
            'cause' => $violation->getCause(),
            'constraint' => get_class($violation->getConstraint()),
        ]);
        return $this;
    }

    /**
     * @return bool
     */
    public function isImportSuccess(): bool
    {
        return $this->importSuccess;
    }

    /**
     * ImportSuccess.
     *
     * @param bool $importSuccess
     * @return Importer
     */
    public function setImportSuccess(bool $importSuccess): Importer
    {
        $this->importSuccess = $importSuccess;
        return $this;
    }

    /**
     * @return bool
     */
    public function isBuildSuccess(): bool
    {
        return $this->buildSuccess;
    }

    /**
     * BuildSuccess.
     *
     * @param bool $buildSuccess
     * @return Importer
     */
    public function setBuildSuccess(bool $buildSuccess): Importer
    {
        $this->buildSuccess = $buildSuccess;
        return $this;
    }

    /**
     * @return int
     */
    public function getProcessedRows(): int
    {
        return $this->processedRows = $this->processedRows ?: 0;
    }

    /**
     * incrementProcessedRows
     * @return Importer
     */
    public function incrementProcessedRows(): Importer
    {
        return $this->setProcessedRows($this->getProcessedRows() + 1);
    }

    /**
     * ProcessedRows.
     *
     * @param int $processedRows
     * @return Importer
     */
    public function setProcessedRows(int $processedRows): Importer
    {
        $this->processedRows = $processedRows;
        return $this;
    }

    /**
     * getProcessedErrorRows
     * @return int
     */
    public function getProcessedErrorRows(): int
    {
        return $this->processedErrorRows = $this->processedErrorRows ?: 0;
    }

    /**
     * incrementProcessedErrorRows
     * @return Importer
     */
    public function incrementProcessedErrorRows(): Importer
    {
        return $this->setProcessedErrorRows($this->getProcessedErrorRows() + 1);
    }

    /**
     * ProcessedErrorRows.
     *
     * @param int $processedErrorRows
     * @return Importer
     */
    public function setProcessedErrorRows(int $processedErrorRows): Importer
    {
        $this->processedErrorRows = $processedErrorRows;
        return $this;
    }

    /**
     * @return int
     */
    public function getProcessedErrors(): int
    {
        return $this->processedErrors = $this->processedErrors ?: 0;
    }

    /**
     * ProcessedErrors.
     *
     * @param int $processedErrors
     * @return Importer
     */
    public function setProcessedErrors(int $processedErrors): Importer
    {
        $this->processedErrors = $processedErrors;
        return $this;
    }

    /**
     * incrementProcessedErrors
     * @return Importer
     */
    private function incrementProcessedErrors(): Importer
    {
        return $this->setProcessedErrors($this->getProcessedErrors() + 1);
    }

    /**
     * @return int
     */
    public function getProcessedWarnings(): int
    {
        return $this->processedWarnings = $this->processedWarnings ?: 0;
    }

    /**
     * ProcessedWarnings.
     *
     * @param int $processedWarnings
     * @return Importer
     */
    public function setProcessedWarnings(int $processedWarnings): Importer
    {
        $this->processedWarnings = $processedWarnings;
        return $this;
    }

    /**
     * incrementProcessedErrors
     * @return Importer
     */
    private function incrementProcessedWarnings(): Importer
    {
        return $this->setProcessedWarnings($this->getProcessedWarnings() + 1);
    }

    /**
     * @return bool
     */
    public function isDatabaseSuccess(): bool
    {
        return $this->databaseSuccess;
    }

    /**
     * DatabaseSuccess.
     *
     * @param bool $databaseSuccess
     * @return Importer
     */
    public function setDatabaseSuccess(bool $databaseSuccess): Importer
    {
        $this->databaseSuccess = $databaseSuccess;
        return $this;
    }

    /**
     * getInserts
     * @return int
     */
    public function getInserts(): int
    {
        return $this->inserts =  $this->inserts ?: 0;
    }

    /**
     * incrementInserts
     * @return Importer
     */
    public function incrementInserts(): Importer
    {
        return $this->setInserts($this->getInserts() + 1);
    }

    /**
     * setInserts
     * @param int $inserts
     * @return Importer
     */
    public function setInserts(int $inserts): Importer
    {
        $this->inserts = $inserts;
        return $this;
    }

    /**
     * @return int
     */
    public function getInsertsSkipped(): int
    {
        return $this->inserts_skipped =  $this->inserts_skipped ?: 0;
    }

    /**
     * InsertsSkipped.
     *
     * @param int $inserts_skipped
     * @return Importer
     */
    public function incrementInsertsSkipped(): Importer
    {
        return $this->setInsertsSkipped($this->getInsertsSkipped() + 1);
;    }

    /**
     * InsertsSkipped.
     *
     * @param int $inserts_skipped
     * @return Importer
     */
    public function setInsertsSkipped(int $inserts_skipped): Importer
    {
        $this->inserts_skipped = $inserts_skipped;
        return $this;
    }

    /**
     * @return int
     */
    public function getUpdates(): int
    {
        return $this->updates = $this->updates ?: 0;
    }

    /**
     * incrementUpdates
     * @return Importer
     */
    public function incrementUpdates(): Importer
    {
        return $this->setUpdates($this->getUpdates() + 1);
    }

    /**
     * Updates.
     *
     * @param int $updates
     * @return Importer
     */
    public function setUpdates(int $updates): Importer
    {
        $this->updates = $updates;
        return $this;
    }

    /**
     * @return int
     */
    public function getUpdatesSkipped(): int
    {
        return $this->updates_skipped = $this->updates_skipped ?: 0;
    }

    /**
     * incrementUpdatesSkipped
     * @return Importer
     */
    public function incrementUpdatesSkipped(): Importer
    {
        return $this->setUpdatesSkipped($this->getUpdatesSkipped() + 1);
    }

    /**
     * UpdatesSkipped.
     *
     * @param int $updates_skipped
     * @return Importer
     */
    public function setUpdatesSkipped(int $updates_skipped): Importer
    {
        $this->updates_skipped = $updates_skipped;
        return $this;
    }
}