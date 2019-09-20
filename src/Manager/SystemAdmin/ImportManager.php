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
use App\Manager\Entity\ImportReport;
use App\Provider\ProviderFactory;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormInterface;
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
    public function prepareStep2(ImportReport $record, ImportRun $importRun, FormInterface $form)
    {
        $columnOrderLast = [];
        if ($importRun->getColumnOrder() === 'last') {
            $columnOrderLast = ProviderFactory::create(ImportRecord::class)->findLastColumnOrderByName($record->getDetail('type'));
        }


        $this->getImporter()->setFieldDelimiter($importRun->getFieldDelimiter());
        $this->getImporter()->setStringEnclosure($importRun->getStringEnclosure());
        
        $csvData = $this->getImporter()->readFileIntoCSV($importRun->getFile());
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
/**
        $form->addRow()->addContent('&nbsp;');

        // COLUMN SELECTION
        if (!empty($record->getAllFields())) {
            $table = $form->addRow()->addTable()->setClass('colorOddEven fullWidth');

            $header = $table->addHeaderRow();
            $header->addContent(__('Field Name'));
            $header->addContent(__('Type'));
            $header->addContent(__('Column'));
            $header->addContent(__('Example'));

            $count = 0;

            $defaultColumns = function ($fieldName) use (&$record, $mode) {
                $columns = [];

                if ($record->isFieldRequired($fieldName) == false || ($mode == 'update' && !$record->isFieldUniqueKey($fieldName))) {
                    $columns[Importer::COLUMN_DATA_SKIP] = '[ '.__('Skip this Column').' ]';
                }
                if ($record->getField($fieldName, 'custom')) {
                    $columns[Importer::COLUMN_DATA_CUSTOM] = '[ '.__('Custom').' ]';
                }
                if ($record->getField($fieldName, 'function')) {
                    $columns[Importer::COLUMN_DATA_FUNCTION] = '[ '.__('Generate').' ]';
                    //data-function='". $record->getField($fieldName, 'function') ."'
                }
                return $columns;
            };

            $columns = array_reduce(range(0, count($headings)-1), function ($group, $index) use (&$headings) {
                $group[strval($index)." "] = $headings[$index];
                return $group;
            }, array());

            $columnIndicators = function ($fieldName) use (&$record, $mode) {
                $output = '';
                if ($record->isFieldRequired($fieldName) && !($mode == 'update' && !$record->isFieldUniqueKey($fieldName))) {
                    $output .= " <strong class='highlight'>*</strong>";
                }
                if ($record->isFieldUniqueKey($fieldName)) {
                    $output .= "<img title='" . __('Must be unique') . "' src='./themes/Default/img/target.png' style='float: right; width:14px; height:14px;margin-left:4px;'>";
                }
                if ($record->isFieldRelational($fieldName)) {
                    $relationalTable = $record->getField($fieldName, 'relationship')['table'] ?? '';
                    $output .= "<img title='" .__('Relationship') .': '.$relationalTable. "' src='./themes/Default/img/refresh.png' style='float: right; width:14px; height:14px;margin-left:4px;'>";
                }
                return $output;
            };

            foreach ($record->getAllFields() as $fieldName) {
                if ($record->isFieldHidden($fieldName)) {
                    $columnIndex = Importer::COLUMN_DATA_HIDDEN;
                    if ($record->isFieldLinked($fieldName)) {
                        $columnIndex = Importer::COLUMN_DATA_LINKED;
                    }
                    if (!empty($record->getField($fieldName, 'function'))) {
                        $columnIndex = Importer::COLUMN_DATA_FUNCTION;
                    }

                    $form->addHiddenValue("columnOrder[$count]", $columnIndex);
                    $count++;
                    continue;
                }

                $selectedColumn = '';
                if ($columnOrder == 'linear' || $columnOrder == 'linearplus') {
                    $selectedColumn = ($columnOrder == 'linearplus')? $count+1 : $count;
                } elseif ($columnOrder == 'last') {
                    $selectedColumn = isset($columnOrderLast[$count])? $columnOrderLast[$count] : '';
                } elseif ($columnOrder == 'guess' || $columnOrder == 'skip') {
                    foreach ($headings as $index => $columnName) {
                        if (mb_strtolower($columnName) == mb_strtolower($fieldName) || mb_strtolower($columnName) == mb_strtolower($record->getField($fieldName, 'name'))) {
                            $selectedColumn = $index;
                            break;
                        }
                    }
                }

                if ($columnOrder == 'skip' && !($record->isFieldRequired($fieldName) && !($mode == 'update' && !$record->isFieldUniqueKey($fieldName)))) {
                    $selectedColumn = Importer::COLUMN_DATA_SKIP;
                }

                $row = $table->addRow();
                $row->addContent(__($record->getField($fieldName, 'name')))
                    ->wrap('<span class="'.$record->getField($fieldName, 'desc').'">', '</span>')
                    ->append($columnIndicators($fieldName));
                $row->addContent($record->readableFieldType($fieldName));
                $row->addSelect('columnOrder['.$count.']')
                    ->setID('columnOrder'.$count)
                    ->fromArray($defaultColumns($fieldName))
                    ->fromArray($columns)
                    ->required()
                    ->setClass('columnOrder mediumWidth')
                    ->selected($selectedColumn)
                    ->placeholder();
                $row->addTextField('columnText['.$count.']')
                    ->setID('columnText'.$count)
                    ->setClass('shortWidth columnText')
                    ->readonly()
                    ->disabled();

                $count++;
            }
        }

        $form->addRow()->addContent('&nbsp;');

        // CSV PREVIEW
        $table = $form->addRow()->addTable()->setClass('smallIntBorder fullWidth');

        $row = $table->addRow();
        $row->addLabel('csvData', __('Data'));
        $row->addTextArea('csvData')->setRows(4)->setCols(74)->setClass('')->readonly()->setValue($csvData);

        $row = $table->addRow();
        $row->addFooter();
        $row->addSubmit();

        echo $form->getOutput();  */
    }
}