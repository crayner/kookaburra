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

use App\Entity\Setting;
use App\Manager\Entity\ImportReport;
use App\Provider\ProviderFactory;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Finder\Finder;
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
}