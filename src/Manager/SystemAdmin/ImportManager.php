<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 16/09/2019
 * Time: 12:14
 */

namespace App\Manager\SystemAdmin;

use App\Entity\ImportRecord;
use App\Entity\Setting;
use App\Form\Entity\ImportRun;
use App\Manager\Entity\ImportRow;
use App\Manager\Entity\ImportReport;
use App\Provider\ProviderFactory;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Yaml\Yaml;

/**
 * Class ImportManager
 * @package App\Manager\SystemAdmin
 */
class ImportManager
{
    /**
     * @var ArrayCollection
     */
    private $importReports;

    /**
     * @var bool 
     */
    private $dataExport = false;

    /**
     * @var bool 
     */
    private $dataExportAll = false;

    /**
     * @var Importer
     */
    private $importer;

    /**
     * ImportManager constructor.
     * @param Importer $importer
     */
    public function __construct(Importer $importer)
    {
        $this->importer = $importer;
    }

    /**
     * Loads all YAML files from a folder and creates an ImportReport object for each
     *
     * @param   Object  PDO Connection
     * @return  array   2D array of ImportReport objects
     */
    public function loadImportReportList(bool $validateStructure = false): ArrayCollection
    {
        $finder = new Finder();
        // Get the built-in import definitions
        $defaultFiles = $finder->files()->in($this->getImportReportDir())->name(['*.yaml', '*.yml']);

        // Create ImportReport objects for each file
        if ($finder->hasResults()) {
            foreach ($defaultFiles as $file) {
                $fileData = Yaml::parse(file_get_contents($file->getRealPath()));

                if (isset($fileData['details']) && isset($fileData['details']['type'])) {
                    $fileData['details']['grouping'] = (isset($fileData['access']['module'])) ? $fileData['access']['module'] : 'General';
                    $this->addImportReport($fileData['details']['type'], new ImportReport($fileData, $validateStructure));
                }
            }
        }

        if (! is_dir($this->getCustomImportReportDir()))
            mkdir($this->getCustomImportReportDir(), 0755, true) ;

        $finder = new Finder();
        // Get the user-defined custom definitions
        $customFiles = $finder->files()->in($this->getCustomImportReportDir())->name(["*.yaml", '*.yml']);

        if ($finder->hasResults()) {
            foreach ($customFiles as $file) {
                $fileData = Yaml::parse(file_get_contents($file->getRealPath()));

                if (isset($fileData['details']) && isset($fileData['details']['type'])) {
                    $fileData['details']['grouping'] = '* Custom Imports';
                    $fileData['details']['custom'] = true;
                    $this->addImportReport($fileData['details']['type'], new ImportReport($fileData, $validateStructure));
                }
            }
        }

        $this->sortImportReports();

        return $this->getImportReports();
    }

    /**
     * getImportReportDir
     * @return string
     */
    public function getImportReportDir()
    {
        return realpath(__DIR__ . "/resources/imports");
    }

    /**
     * getImportReports
     * @return ArrayCollection
     */
    public function getImportReports(): ArrayCollection
    {
        return $this->importReports = $this->importReports ?: new ArrayCollection();
    }

    /**
     * ImportReports.
     *
     * @param ArrayCollection $importReports
     * @return ImportManager
     */
    public function setImportReports(ArrayCollection $importReports): ImportManager
    {
        $this->importReports = $importReports;
        return $this;
    }

    /**
     * addImportReport
     * @param string $key
     * @param ImportReport $type
     * @return ImportManager
     */
    public function addImportReport(string $key, ImportReport $type): ImportManager
    {
        $this->getImportReports()->set($key,$type);
        return $this;
    }

    /**
     * getCustomImportReportDir
     * @return string
     */
    public function getCustomImportReportDir()
    {
        $customFolder = ProviderFactory::create(Setting::class)->getSettingByScopeAsString('Data Admin', 'importCustomFolderLocation');

        return realpath(__DIR__ . '/../../../public/uploads').rtrim($customFolder, '/ ');
    }

    /**
     * sortImportReports
     * @return int
     */
    protected function sortImportReports()
    {
        $iterator = $this->getImportReports()->getIterator();

        $iterator->uasort(
            function ($a, $b) {
                return ($a->getDetail('grouping').$a->getDetail('category').$a->getDetail('name') < $b->getDetail('grouping').$b->getDetail('category').$b->getDetail('name')) ? -1 : 1;
            }
        );

        $this->importReports = new ArrayCollection(iterator_to_array($iterator, false));
    }

    /**
     * @return bool
     */
    public function isDataExport(): bool
    {
        return $this->dataExport;
    }

    /**
     * DataExport.
     *
     * @param bool $dataExport
     * @return ImportManager
     */
    public function setDataExport(bool $dataExport): ImportManager
    {
        $this->dataExport = $dataExport;
        return $this;
    }

    /**
     * @return bool
     */
    public function isDataExportAll(): bool
    {
        return $this->dataExportAll;
    }

    /**
     * DataExportAll.
     *
     * @param bool $dataExportAll
     * @return ImportManager
     */
    public function setDataExportAll(bool $dataExportAll): ImportManager
    {
        $this->dataExportAll = $dataExportAll;
        return $this;
    }

    /**
     * getImportReport
     * @param string $reportName
     * @return ImportReport|null
     */
    public function getImportReport(string $reportName): ?ImportReport
    {
        // Check custom first, this allows for local overrides
        $path = $this->getCustomImportReportDir().'/'.$reportName.'.yaml';
        if (!file_exists($path)) {
            // Next check the built-in import types folder
            $path = $this->getImportReportDir().'/'.$reportName.'.yaml';

            // Finally fail if nothing is found
            if (!file_exists($path)) {
                $path = $this->getCustomImportReportDir().'/'.$reportName.'.yml';
                if (!file_exists($path)) {
                    // Next check the built-in import types folder
                    $path = $this->getImportReportDir().'/'.$reportName.'.yml';

                    // Finally fail if nothing is found
                    if (!file_exists($path)) {
                        return null;
                    }
                }
            }
        }

        $fileData = Yaml::parse(file_get_contents($path));

        return new ImportReport($fileData);
    }

    /**
     * @return Importer
     */
    public function getImporter(): Importer
    {
        return $this->importer;
    }

    /**
     * prepareStep2
     * @param ImportReport $record
     * @param ImportRun $importRun
     * @param FormInterface $form
     */
    public function prepareStep2(ImportReport $record, ImportRun $importRun, FormInterface $form, Request $request)
    {
        $columnOrderLast = [];
        if ($importRun->getColumnOrder() === 'last') {
            $columnOrderLast = ProviderFactory::create(ImportRecord::class)->findLastColumnOrderByName($record->getDetail('type'));
        }


        $this->getImporter()->setFieldDelimiter($importRun->getFieldDelimiter());
        $this->getImporter()->setStringEnclosure($importRun->getStringEnclosure());

        if ($importRun->getCsvData() === null && $importRun->getFile() !== null) {
            $importRun->setCsvData($this->getImporter()->readFileIntoCSV($importRun->getFile()));
            unlink($importRun->getFile()->getRealPath());
            $importRun->setFile(null);
        }
        if ($importRun->getCsvData() === null) {
            $importStep2 = $request->get('import_step2');
            $importRun->setCsvData($importStep2['csvData']);
            $this->getImporter()->setHeaderFirstLine($importRun->getCsvData());
        }

        $headings = $this->getImporter()->getHeaderRow();
        $headers = [];
        foreach($headings as $name)
            $headers[$name] = $name;

        $firstLine = $this->getImporter()->getFirstRow();

        // SYNC SETTINGS
        if (in_array($importRun->getModes(), ["sync", "update"])) {
            $lastFieldValue = ($importRun->getColumnOrder() === 'last' && isset($columnOrderLast['syncField'])) ? $columnOrderLast['syncField'] : 'N';
            $lastColumnValue = ($importRun->getColumnOrder() === 'last' && isset($columnOrderLast['syncColumn'])) ? $columnOrderLast['syncColumn'] : '';

            if ($importRun->getColumnOrder() == 'linearplus') {
                $lastFieldValue = true;
                $lastColumnValue = $record->getPrimaryKey();
            }

            $form->add('syncColumn', ChoiceType::class,
                [
                    'label' => 'Primary Key',
                    'data' => $lastColumnValue,
                    'help' => '{table} has a primary key of {key}',
                    'help_translation_parameters' => ['{table}' => $record->getDetail('table'), '{key}' => $record->getPrimaryKey()],
                    'choices' => $headers,
                    'placeholder' => 'Please select...',
                    'row_class' => 'flex flex-col sm:flex-row justify-between content-center p-0 syncDetails',
                ]
            );
        }

        $count = 0;

        $defaultColumns = function ($fieldName) use ($record, $importRun) {
            $columns = [];

            if (!$record->isFieldRequired($fieldName) || ($importRun->getModes() === 'update' && !$record->isFieldUniqueKey($fieldName))) {
                $columns[Importer::COLUMN_DATA_SKIP] = 'Skip this Column';
            }
            if ($record->getField($fieldName, 'custom')) {
                $columns[Importer::COLUMN_DATA_CUSTOM] = 'Custom';
            }
            if ($record->getField($fieldName, 'function')) {
                $columns[Importer::COLUMN_DATA_FUNCTION] = 'Generate';
            }
            return $columns;
        };

        $columns = array_reduce(range(0, count($headings)-1), function ($group, $index) use (&$headings) {
            $group[strval($index)." "] = $headings[$index];
            return $group;
        }, array());

        $columnIndicators = function ($fieldName) use ($record, $importRun) {
            $output = [];
            if ($record->isFieldRequired($fieldName) && !($importRun->getModes() === 'update' && !$record->isFieldUniqueKey($fieldName))) {
                $output[] = 'required';
            }
            if ($record->isFieldUniqueKey($fieldName)) {
                $output[] = 'unique';
            }
            if ($record->isFieldRelational($fieldName)) {
                $relationalTable = $record->getField($fieldName, 'relationship')['table'] ?? '';
                $output[] = 'relational';
            }
            return $output;
        };

        foreach ($record->getAllFields() as $fieldName) {
            $row = new ImportRow();
            $row->setFlags($columnIndicators($fieldName));
            if ($record->isFieldHidden($fieldName)) {
                $columnIndex = Importer::COLUMN_DATA_HIDDEN;
                if ($record->isFieldLinked($fieldName)) {
                    $columnIndex = Importer::COLUMN_DATA_LINKED;
                }
                if (!empty($record->getField($fieldName, 'function'))) {
                    $columnIndex = Importer::COLUMN_DATA_FUNCTION;
                }
                $row->setOrder($columnIndex)->setCount($count++);
                $importRun->addColumnRow($row);
                continue;
            }

            $selectedColumn = '';
            if ($importRun->getColumnOrder() === 'linear' || $importRun->getColumnOrder() === 'linearplus') {
                $selectedColumn = ($importRun->getColumnOrder() === 'linearplus')? ++$count : $count;
            } elseif ($importRun->getColumnOrder() === 'last') {
                $selectedColumn = isset($columnOrderLast[$count])? $columnOrderLast[$count] : '';
            } elseif ($importRun->getColumnOrder() === 'guess' || $importRun->getColumnOrder() === 'skip') {
                foreach ($headings as $index => $columnName) {
                    if (mb_strtolower($columnName) == mb_strtolower($fieldName) || mb_strtolower($columnName) == mb_strtolower($record->getField($fieldName, 'name'))) {
                        $selectedColumn = $index;
                        break;
                    }
                }
            }

            if ($importRun->getColumnOrder() === 'skip' && !($record->isFieldRequired($fieldName) && !($importRun->getModes() === 'update' && !$record->isFieldUniqueKey($fieldName)))) {
                $selectedColumn = Importer::COLUMN_DATA_SKIP;
            }

            $key = array_search($record->getField($fieldName, 'name'), $headings);

            $row->setName($record->getField($fieldName, 'name'))
                ->setFieldType($record->readableFieldType($fieldName))
                ->setCount($count++)
                ->setOrder($selectedColumn)
                ->setColumnChoices($defaultColumns($fieldName), $columns)
                ->setExample($firstLine[$key] ?: null)
            ;

            $importRun->addColumnRow($row);

        }

        $form->add('columnCollection', CollectionType::class,
            [
                'label' => false,
                'entry_type' => ChoiceType::class,
                'entry_options' => [
                    'choices' => array_flip($row->getColumnChoices()),
                    'placeholder' => 'Please select...',
                ],
            ]
        );

        $form->add('csvData', TextareaType::class,
            [
                'label' => 'Data',
                'help' => 'This value cannot be changed.',
                'attr' => [
                    'rows' => 4,
                    'cols' => 74,
                    'readonly' => 'readonly',
                ],
            ]
        );
    }

    /**
     * prepareStep2
     * @param ImportReport $record
     * @param ImportRun $importRun
     * @param FormInterface $form
     */
    public function prepareStep3(ImportReport $record, ImportRun $importRun, FormInterface $form, Request $request)
    {

    }
}