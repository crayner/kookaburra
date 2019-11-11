<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 3/09/2019
 * Time: 10:54
 */

namespace App\Manager;

use App\Entity\Setting;
use App\Provider\ProviderFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class ExcelManager
 * @package App\Manager
 */
class ExcelManager extends Spreadsheet
{
    /**
     * @var null|string
     */
    private	$fileName;

    /**
     * @var string
     */
    private	$mimeType = 'application/vnd.ms-excel';

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * setHeader
     */
    private function setHeader()//this function used to set the header variable
    {
        header('Content-type: ' . $this->mimeType);
        header('Content-Disposition: attachment; filename="' . $this->fileName . '"');
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: private", false);
    }

    /**
     * Export with Query
     *
     * @version	27th May 2016
     * @since	8th April 2016
     * @param array $result
     * @param string $excel_file_name
     * @param bool $headerRow
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    function exportWithArray(array $result, string $excel_file_name, bool $headerRow = false)
    {
        $this->defineWorkSheet($excel_file_name);
        $this->getProperties()->setTitle($this->translate("Kookaburra Query Dump"));
        $this->getProperties()->setSubject($this->translate("Kookaburra Query Dump"));
        $this->getProperties()->setDescription($this->translate("Dump of Query Results generated by Kookaburra"))
            ->setCompany(ProviderFactory::create(Setting::class)->getSettingByScopeAsString('System', 'organisationName'))
        ;

        $rNum = 0;
        foreach($result as $row) {
            $rNum++;
            if ($rNum === 1)
            {
                if ($headerRow) {
                    $cNum = 0;
                    foreach($row as $name)
                    {
                        $cNum++ ;
                        $this->getActiveSheet()->setCellValueByColumnAndRow($cNum, $rNum, $name);
                    }
                    $this->getActiveSheet()->getStyle('1:1')->getFont()->setBold(true);
                    continue;
                }
                // Row 1 is the headers.
                $cNum = 0;
                foreach($row as $name=>$value)
                {
                    $cNum++ ;
                    $this->getActiveSheet()->setCellValueByColumnAndRow($cNum, $rNum, $name);
                }
                $this->getActiveSheet()->getStyle('1:1')->getFont()->setBold(true);
                $rNum++;
            }
            $cNum = 0 ;
            foreach($row as $value )
            {
                $cNum++ ;
                $this->getActiveSheet()->setCellValueByColumnAndRow($cNum, $rNum, $value);
            }
        }

        foreach(range(0, $cNum) as $col )
            $this->getActiveSheet()->getColumnDimensionByColumn($col)->setAutoSize(true);

        $this->exportWorksheet();
    }

    /**
     * define Worksheet
     *
     * @version	8th April 2016
     * @since	8th April 2016
     * @param	string	File Name
     * @return	void
     */
    public function defineWorksheet($fileName)
    {
        $this->getProperties()->setCreator($this->translate("Kookaburra using PHPOffice/PHPSpreadseet"));
        $this->getProperties()->setLastModifiedBy($this->translate("Kookaburra"));
        $this->getProperties()->setTitle($this->translate("Office 2007 XLSX Test Document"));
        $this->getProperties()->setSubject($this->translate("Office 2007 XLSX Test Document"));
        $this->getProperties()->setDescription($this->translate('This information is confidential. Generated by Kookaburra (https://kookaburra.craigrayner.org).'))
            ->setCompany(ProviderFactory::create(Setting::class)->getSettingByScopeAsString('System', 'organisationName'))
        ;
        $fileName = '' === $fileName ? 'No_Name_Set.xlsx' : $fileName;
        $this->setFileName($fileName);
    }

    /**
     * export Worksheet
     *
     * @version	9th April 2016
     * @since	9th April 2016
     * @param	boolean	Use Excel2007 or >
     * @return	void
     */
    public function exportWorksheet($openXML = true)
    {
        // Instantiate a Writer to create an OfficeOpenXML Excel .xlsx file
        // Write the Excel file to filename some_excel_file.xlsx in the current directory
        if ($openXML)
            $objWriter = new Xlsx($this);
        else
        {
            $this->fileName = substr($this->fileName, 0, -1);
            $objWriter = new Xls($this);
        }
        // Write the Excel file to filename some_excel_file.xlsx in the current directory
        $this->setHeader();

        $objWriter->save('php://output');
        die();
    }

    /**
     * construct
     *
     * @version	9th April 2016
     * @since	9th April 2016
     * @param	string	File Name
     * @return	void
     */
    public function __construct(TranslatorInterface $translator, ?string $fileName = null)
    {
        parent::__construct();
        $this->translator = $translator;
        $this->defineWorksheet($fileName);
    }

    /**
     * cell Colour
     *
     * @version	11th April 2016
     * @since	11th APril 2016
     * @param	string	Cell/s
     * @param	string	Colour
     * @param	object	Chaining
     */
    public function cellColour($cells, $colour)
    {
        $this->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray( array(
            'type' => Fill::FILL_SOLID,
            'startcolor' => array(
                'rgb' => $colour
            )
        ));
        return $this;
    }

    /**
     * cell Color  (American)
     *
     * @version	11th April 2016
     * @since	11th April 2016
     * @param	string	Cell/s
     * @param	string	Colour
     * @param	object	Chaining
     */
    public function cellColor($cells, $colour)
    {
        return $this->cellColour($cells, $colour);
    }

    /**
     * Estimate Cell Count in Spreadsheet
     *
     * @version	14th April 2016
     * @since	14th April 2016
     * @param   array $list
     * return	integer	Estimated Cell Count
     */
    public function estimateCellCount(array $list): int
    {
        return intval(count($list) * count(reset($list)));
    }

    /**
     * cell Font Colour
     *
     * @version	14th April 2016
     * @since	14th APril 2016
     * @param	string	Cell/s
     * @param	string	Colour
     * @param	object	Chaining
     */
    public function cellFontColour($cells, $colour)
    {
        $styleArray = array(
            'font'  => array(
                'color' => array('rgb' => $colour),
            )
        );
        $this->getActiveSheet()->getStyle($cells)->applyFromArray($styleArray);
        return $this;
    }

    /**
     * cell Font Color  (American)
     *
     * @version	14th April 2016
     * @since	14th April 2016
     * @param	string	Cell/s
     * @param	string	Colour
     * @param	object	Chaining
     */
    public function cellFontColor($cells, $colour)
    {
        return $this->cellFontColour($cells, $colour);
    }

    /**
     * Get a column letter name for given number
     *
     * @version	v13
     * @since	v13
     * @param	int		number
     * @return	string	letter
     */
    public function num2alpha($n) {
        for($r = ""; $n >= 0; $n = intval($n / 26) - 1)
            $r = chr($n%26 + 0x41) . $r;
        return $r;
    }

    /**
     * @return mixed
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * FileName.
     *
     * @param mixed $fileName
     * @return ExcelManager
     */
    public function setFileName($fileName)
    {
        $exportFileType = ProviderFactory::create(Setting::class)->getSettingByScopeAsString('System Admin', 'exportDefaultFileType', 'Excel2007');
        if (empty($exportFileType)) {
            $exportFileType = 'Excel2007';
        }

        switch ($exportFileType) {
            case 'Excel2007':
                $fileName .= '.xlsx';
                $mimeType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
                break;
            case 'Excel5':
                $fileName .= '.xls';
                $mimeType = 'application/vnd.ms-excel';
                break;
            case 'OpenDocument':
                $fileName .= '.ods';
                $mimeType = 'application/vnd.oasis.opendocument.spreadsheet';
                break;
           default:
                $fileName .= '.csv';
                $mimeType = 'text/csv';
        }
        $this->fileName = $fileName;
        $this->mimeType = $mimeType;
        return $this;
    }

    /**
     * translate
     * @param string $id
     * @param array $params
     * @param string $domain
     * @return string
     */
    private function translate(string $id, array $params = [], string $domain = 'messages'): string
    {
        return $this->translator->trans($id, $params, $domain);
    }
}