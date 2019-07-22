<?php
/*
Gibbon, Flexible & Open School System
Copyright (C) 2010, Ross Parker

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see <http://www.gnu.org/licenses/>.

Certain code below was taken from the Excel examples, which are licensed under the GNU GPL.
*/

use Gibbon\Services\Format;

//Increase max execution time, as this stuff gets big
ini_set('max_execution_time', 600);

//System includes
include '../../gibbon.php';
include '../../version.php';

//Module includes
include './moduleFunctions.php';

if (isActionAccessible($guid, $connection2, '/modules/Tracking/dataPoints.php') == false) {
    //Acess denied
    echo "<div class='error'>";
    echo __('You do not have access to this action.');
    echo '</div>';
} else {
    // Create new Excel object
    $excel = new \Gibbon\Excel();

    //Create border style for use locale_filter_matches
    $style_border = array('borders' => array('right' => array('style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, 'color' => array('argb' => '766f6e')), 'left' => array('style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, 'color' => array('argb' => '766f6e')), 'top' => array('style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, 'color' => array('argb' => '766f6e')), 'bottom' => array('style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, 'color' => array('argb' => '766f6e'))));
    $style_head_fill = array('fill' => array('type' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'color' => array('rgb' => 'B89FE2')));
    $style_head_fill2 = array('fill' => array('type' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'color' => array('rgb' => 'C5D9F1')));
    $style_head_fill3 = array('fill' => array('type' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'color' => array('rgb' => '8DB4E2')));
    $style_head_fill4 = array('fill' => array('type' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'color' => array('rgb' => '538CD6')));
    $style_head_fill5 = array('fill' => array('type' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'color' => array('rgb' => 'EBF1DF')));
    $style_head_fill6 = array('fill' => array('type' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'color' => array('rgb' => 'D8E3BE')));
    $style_head_fill7 = array('fill' => array('type' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'color' => array('rgb' => 'C4D69E')));

    // Set document properties
    $excel->getProperties()->setCreator(Format::name('', $_SESSION[$guid]['preferredName'], $_SESSION[$guid]['surname'], 'Staff'))
         ->setLastModifiedBy(Format::name('', $_SESSION[$guid]['preferredName'], $_SESSION[$guid]['surname'], 'Staff'))
         ->setTitle(__('Assessment Data Points'))
         ->setDescription(__('This information is confidential. Generated by Gibbon (https://gibbonedu.org).'));

     //Get and check settings
    $externalAssessmentDataPoints = unserialize(getSettingByScope($connection2, 'Tracking', 'externalAssessmentDataPoints'));
    $internalAssessmentDataPoints = unserialize(getSettingByScope($connection2, 'Tracking', 'internalAssessmentDataPoints'));
    $internalAssessmentTypes = explode(',', getSettingByScope($connection2, 'Formal Assessment', 'internalAssessmentTypes'));
    if (count($externalAssessmentDataPoints) == 0 and count($internalAssessmentDataPoints) == 0) { //Seems like things are not configured, so show error
        $excel->setActiveSheetIndex(0)->setCellValue('A1', __('An error has occurred.'));
    } else { //Seems like things are configured, so proceed
        //Get year groups and create sheets
        $yearGroups = getYearGroups($connection2);
        if ($yearGroups == '') {
            $excel->setActiveSheetIndex(0)->setCellValue('A1', __('An error has occurred.'));
        } else {
            //GET ALL INTERNAL ASSESSMENT RESULTS FOR ALL STUDENTS, AND CACHE THEM FOR USE LATER
            $internalResults = array();
            try {
                $data = array();
                $sql = 'SELECT gibbonStudentEnrolment.gibbonYearGroupID, gibbonCourse.name AS course, gibbonInternalAssessmentColumn.type, gibbonPersonIDStudent, attainmentValue, completeDate, gibbonInternalAssessmentColumn.name AS assessment FROM gibbonInternalAssessmentEntry JOIN gibbonPerson ON (gibbonInternalAssessmentEntry.gibbonPersonIDStudent=gibbonPerson.gibbonPersonID) JOIN gibbonInternalAssessmentColumn ON (gibbonInternalAssessmentEntry.gibbonInternalAssessmentColumnID=gibbonInternalAssessmentColumn.gibbonInternalAssessmentColumnID) JOIN gibbonCourseClass ON (gibbonInternalAssessmentColumn.gibbonCourseClassID=gibbonCourseClass.gibbonCourseClassID) JOIN gibbonCourse ON (gibbonCourseClass.gibbonCourseID=gibbonCourse.gibbonCourseID) JOIN gibbonStudentEnrolment ON (gibbonStudentEnrolment.gibbonPersonID=gibbonPerson.gibbonPersonID AND gibbonStudentEnrolment.gibbonSchoolYearID=gibbonCourse.gibbonSchoolYearID) ORDER BY gibbonCourse.name, gibbonInternalAssessmentColumn.name, gibbonPersonIDStudent, completeDate DESC';
                $result = $connection2->prepare($sql);
                $result->execute($data);
            } catch (PDOException $e) {
            }
            while ($row = $result->fetch()) {
                $internalIndex = $row['gibbonYearGroupID'].'-'.$row['course'].'-'.$row['type'].'-'.$row['gibbonPersonIDStudent'].'-'.$row['assessment'];
                $internalResults[$internalIndex] = $row['attainmentValue'];
            }

            //GET ALL EXTERNAL ASSESSMENT RESULTS FOR ALL STUDENTS, AND CACHE THEM FOR USE LATER
            $externalResults = array();
            try {
                $data = array();
                $sql = 'SELECT gibbonExternalAssessment.nameShort AS assessment, gibbonExternalAssessmentField.name AS field, gibbonExternalAssessmentField.category, gibbonPerson.gibbonPersonID, gibbonScaleGrade.value, date FROM gibbonExternalAssessmentStudent JOIN gibbonPerson ON (gibbonExternalAssessmentStudent.gibbonPersonID=gibbonPerson.gibbonPersonID) JOIN gibbonExternalAssessment ON (gibbonExternalAssessmentStudent.gibbonExternalAssessmentID=gibbonExternalAssessment.gibbonExternalAssessmentID) JOIN gibbonExternalAssessmentStudentEntry ON (gibbonExternalAssessmentStudentEntry.gibbonExternalAssessmentStudentID=gibbonExternalAssessmentStudent.gibbonExternalAssessmentStudentID) JOIN gibbonExternalAssessmentField ON (gibbonExternalAssessmentStudentEntry.gibbonExternalAssessmentFieldID=gibbonExternalAssessmentField.gibbonExternalAssessmentFieldID) JOIN gibbonScaleGrade ON (gibbonExternalAssessmentStudentEntry.gibbonScaleGradeID=gibbonScaleGrade.gibbonScaleGradeID) ORDER BY gibbonExternalAssessment.nameShort, category, gibbonPersonID, date DESC';
                $result = $connection2->prepare($sql);
                $result->execute($data);
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
            while ($row = $result->fetch()) {
                $externalIndex = $row['assessment'].'-'.$row['category'].'-'.$row['field'].'-'.$row['gibbonPersonID'];
                if (isset($externalResults[$externalIndex]) == false) {
                    $externalResults[$externalIndex] = $row['value'];
                }
            }

            for ($i = 0; $i < count($yearGroups); $i = $i + 2) {
                //SET UP SHEET WITH HEADERS, STUDENT INFORMATION ETC
                $activeRow = 4;
                if ($i > 0) {
                    $excel->createSheet(); //Create sheet
                }
                $excel->setActiveSheetIndex($i / 2);
                $excel->getActiveSheet()->setTitle(__($yearGroups[($i + 1)])); //Rename sheet
                $excel->getActiveSheet()
                    ->setCellValue('A3', __('Username'))
                    ->setCellValue('B3', __('Surname'))
                   ->setCellValue('C3', __('Preferred Name'))
                   ->setCellValue('D3', __('DOB'))
                   ->setCellValue('E3', __('Roll Group'))
                   ->setCellValue('F3', __('Status'));
                foreach (range('A', 'F') as $columnID) {
                    $excel->getActiveSheet()->getStyle($columnID.'3')->applyFromArray($style_border);
                    $excel->getActiveSheet()->getStyle($columnID.'3')->applyFromArray($style_head_fill);
                    $excel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
                }

                $columns = array();
                $activeColumn = 6;
                //GET EXTERNAL ASSESSMENTS/CATEGORIES AND CREATE HEADERS
                try {
                    $data = array('gibbonYearGroupID' => $yearGroups[$i]);
                    $sql = 'SELECT gibbonExternalAssessment.gibbonExternalAssessmentID, gibbonExternalAssessment.nameShort AS assessment, gibbonExternalAssessmentField.category, gibbonExternalAssessmentField.name AS field
						FROM gibbonExternalAssessment
						JOIN gibbonExternalAssessmentField ON (gibbonExternalAssessmentField.gibbonExternalAssessmentID=gibbonExternalAssessment.gibbonExternalAssessmentID)
						ORDER BY gibbonExternalAssessment.name, gibbonExternalAssessmentField.category, gibbonExternalAssessmentField.name';
                    $result = $connection2->prepare($sql);
                    $result->execute($data);
                } catch (PDOException $e) {
                }
                while ($row = $result->fetch()) {
                    foreach ($externalAssessmentDataPoints as $point) {
                        if ($point['gibbonExternalAssessmentID'] == $row['gibbonExternalAssessmentID'] and $point['category'] == $row['category']) {
                            if (!(strpos($point['gibbonYearGroupIDList'], $yearGroups[$i]) === false)) {
                                //Output data
                                $excel->getActiveSheet()->setCellValue(num2alpha($activeColumn).'1', $row['assessment']);
                                $excel->getActiveSheet()->setCellValue(num2alpha($activeColumn).'2', substr($row['category'], (strpos($row['category'], '_') + 1)));
                                $excel->getActiveSheet()->setCellValue(num2alpha($activeColumn).'3', $row['field']);
                                $excel->getActiveSheet()->getStyle(num2alpha($activeColumn).'1')->applyFromArray($style_border);
                                $excel->getActiveSheet()->getStyle(num2alpha($activeColumn).'1')->applyFromArray($style_head_fill7);
                                $excel->getActiveSheet()->getStyle(num2alpha($activeColumn).'1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                                $excel->getActiveSheet()->getStyle(num2alpha($activeColumn).'2')->applyFromArray($style_border);
                                $excel->getActiveSheet()->getStyle(num2alpha($activeColumn).'2')->applyFromArray($style_head_fill6);
                                $excel->getActiveSheet()->getStyle(num2alpha($activeColumn).'2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                                $excel->getActiveSheet()->getStyle(num2alpha($activeColumn).'3')->applyFromArray($style_border);
                                $excel->getActiveSheet()->getStyle(num2alpha($activeColumn).'3')->applyFromArray($style_head_fill5);
                                $excel->getActiveSheet()->getStyle(num2alpha($activeColumn).'3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                                $excel->getActiveSheet()->getColumnDimension(num2alpha($activeColumn))->setAutoSize(true);

                                //Cache column for later user
                                $columns[($activeColumn - 6)]['columnType'] = 'External';
                                $columns[($activeColumn - 6)]['count'] = ($activeColumn - 6);
                                $columns[($activeColumn - 6)]['gibbonExternalAssessmentID'] = $row['gibbonExternalAssessmentID'];
                                $columns[($activeColumn - 6)]['assessment'] = $row['assessment'];
                                $columns[($activeColumn - 6)]['category'] = $row['category'];
                                $columns[($activeColumn - 6)]['field'] = $row['field'];

                                ++$activeColumn;
                            }
                        }
                    }
                }

                //GET INTERNAL ASSESSMENTS AND CREATE HEADERS
                //Get gibbonSchoolYearID list for the school years including and before this year
                $data2 = array();
                $sql2 = '';
                $yearMatch = array();
                $countYear = 1;
                try {
                    $data = array('gibbonSchoolYearID' => $_SESSION[$guid]['gibbonSchoolYearID']);
                    $sql = "SELECT gibbonSchoolYearID
                        FROM gibbonSchoolYear
                        WHERE gibbonSchoolYearID<=:gibbonSchoolYearID
                        ORDER BY sequenceNumber DESC";
                    $result = $connection2->prepare($sql);
                    $result->execute($data);
                } catch (PDOException $e) {}
                while ($row = $result->fetch()) {
                    $yearGroupIndex = (count($yearGroups)-($countYear*2)) - (count($yearGroups)-2-$i);
                    if ($yearGroupIndex >= 0 && $yearGroups[$yearGroupIndex] != '') {

                        $data2['gibbonYearGroupID'.$countYear] = $yearGroups[$yearGroupIndex];
                        $data2['gibbonSchoolYearID'.$countYear] = $row['gibbonSchoolYearID'];
                        $sql2 .= "(SELECT DISTINCT CONCAT(gibbonYearGroup.gibbonYearGroupID) AS gibbonYearGroupID, gibbonYearGroup.name AS yearGroup, sequenceNumber, gibbonCourse.name AS course, gibbonInternalAssessmentColumn.name AS assessment, gibbonInternalAssessmentColumn.type
                            FROM gibbonYearGroup
                                JOIN gibbonCourse ON (gibbonCourse.gibbonYearGroupIDList LIKE concat('%', gibbonYearGroup.gibbonYearGroupID, '%'))
                                JOIN gibbonCourseClass ON (gibbonCourseClass.gibbonCourseID=gibbonCourse.gibbonCourseID)
                                JOIN gibbonInternalAssessmentColumn ON (gibbonInternalAssessmentColumn.gibbonCourseClassID=gibbonCourseClass.gibbonCourseClassID)
                            WHERE gibbonYearGroup.gibbonYearGroupID=:gibbonYearGroupID".$countYear."
                                AND gibbonCourse.gibbonSchoolYearID=:gibbonSchoolYearID".$countYear."
                                AND gibbonInternalAssessmentColumn.completeDate<='".date('Y-m-d')."'
                            ) UNION ";
                        $countYear ++;
                    }
                }
                try {
                    $sql2 = substr($sql2, 0, -7);
                    $sql2 .= ' ORDER BY sequenceNumber, course';
                    $result = $connection2->prepare($sql2);
                    $result->execute($data2);
                } catch (PDOException $e) {}

                while ($row = $result->fetch()) {
                    foreach ($internalAssessmentTypes as $type) {
                        foreach ($internalAssessmentDataPoints as $point) {
                            if ($point['type'] == $type && $row['type'] == $type) {
                                if (!(strpos($point['gibbonYearGroupIDList'], $row['gibbonYearGroupID']) === false)) {
                                    //Output data
                                    $excel->getActiveSheet()->setCellValue(num2alpha($activeColumn).'1', $row['yearGroup']);
                                    $excel->getActiveSheet()->setCellValue(num2alpha($activeColumn).'2', $type."\r\n".$row['assessment']);
                                    $excel->getActiveSheet()->setCellValue(num2alpha($activeColumn).'3', trim(str_replace($row['yearGroup'], '', $row['course'])));
                                    $excel->getActiveSheet()->getStyle(num2alpha($activeColumn).'1')->applyFromArray($style_border);
                                    $excel->getActiveSheet()->getStyle(num2alpha($activeColumn).'1')->applyFromArray($style_head_fill4);
                                    $excel->getActiveSheet()->getStyle(num2alpha($activeColumn).'1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                                    $excel->getActiveSheet()->getStyle(num2alpha($activeColumn).'2')->applyFromArray($style_border);
                                    $excel->getActiveSheet()->getStyle(num2alpha($activeColumn).'2')->applyFromArray($style_head_fill3);
                                    $excel->getActiveSheet()->getStyle(num2alpha($activeColumn).'2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                                    $excel->getActiveSheet()->getStyle(num2alpha($activeColumn).'2')->getAlignment()->setWrapText(true);
                                    $excel->getActiveSheet()->getStyle(num2alpha($activeColumn).'3')->applyFromArray($style_border);
                                    $excel->getActiveSheet()->getStyle(num2alpha($activeColumn).'3')->applyFromArray($style_head_fill2);
                                    $excel->getActiveSheet()->getStyle(num2alpha($activeColumn).'3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                                    $excel->getActiveSheet()->getColumnDimension(num2alpha($activeColumn))->setAutoSize(true);

                                    //Cache column for later user
                                    $columns[($activeColumn - 6)]['columnType'] = 'Internal';
                                    $columns[($activeColumn - 6)]['count'] = ($activeColumn - 6);
                                    $columns[($activeColumn - 6)]['gibbonYearGroupID'] = $row['gibbonYearGroupID'];
                                    $columns[($activeColumn - 6)]['yearGroup'] = $row['yearGroup'];
                                    $columns[($activeColumn - 6)]['type'] = $type;
                                    $columns[($activeColumn - 6)]['course'] = $row['course'];
                                    $columns[($activeColumn - 6)]['assessment'] = $row['assessment'];

                                    ++$activeColumn;

                                }
                            }
                        }
                    }
                }

                //GET STUDENTS AND LIST THEIR DETAILS
                try {
                    $data = array('gibbonSchoolYearID' => $_SESSION[$guid]['gibbonSchoolYearID'], 'gibbonYearGroupID' => $yearGroups[$i]);
                    $sql = "SELECT gibbonPerson.gibbonPersonID, surname, preferredName, username, dob, nameShort AS rollgroup, status FROM gibbonPerson JOIN gibbonStudentEnrolment ON (gibbonStudentEnrolment.gibbonPersonID = gibbonPerson.gibbonPersonID) JOIN gibbonRollGroup ON (gibbonStudentEnrolment.gibbonRollGroupID = gibbonRollGroup.gibbonRollGroupID) WHERE (status='Full' OR status='Left') AND gibbonStudentEnrolment.gibbonSchoolYearID=:gibbonSchoolYearID AND gibbonYearGroupID=:gibbonYearGroupID ORDER BY status, surname, preferredName";
                    $result = $connection2->prepare($sql);
                    $result->execute($data);
                } catch (PDOException $e) {
                    $excel->getActiveSheet()
                        ->setCellValue('A2', __('Your request failed due to a database error.'));
                }
                if ($result->rowCount() < 1) {
                    $excel->getActiveSheet()
                        ->setCellValue('A2', __('There are no records to display.'));
                } else {
                    while ($row = $result->fetch()) {
                        //Set rows headers for students
                        $excel->getActiveSheet()
                            ->setCellValue('A'.$activeRow, $row['username'])
                            ->setCellValue('B'.$activeRow, $row['surname'])
                            ->setCellValue('C'.$activeRow, $row['preferredName'])
                            ->setCellValue('D'.$activeRow, dateConvertBack($guid, $row['dob']))
                            ->setCellValue('E'.$activeRow, $row['rollgroup'])
                            ->setCellValue('F'.$activeRow, $row['status']);
                        $excel->getActiveSheet()->getStyle('A'.$activeRow)->applyFromArray($style_border);
                        $excel->getActiveSheet()->getStyle('B'.$activeRow)->applyFromArray($style_border);
                        $excel->getActiveSheet()->getStyle('C'.$activeRow)->applyFromArray($style_border);
                        $excel->getActiveSheet()->getStyle('D'.$activeRow)->applyFromArray($style_border);
                        $excel->getActiveSheet()->getStyle('E'.$activeRow)->applyFromArray($style_border);
                        $excel->getActiveSheet()->getStyle('F'.$activeRow)->applyFromArray($style_border);

                        //Create cells for each of the columns cached earlier
                        foreach ($columns as $column) {
                            $excel->getActiveSheet()->getStyle(num2alpha($column['count'] + 6).$activeRow)->applyFromArray($style_border);
                            if ($column['columnType'] == 'External') { //Output external assessment data
                                $externalIndex = $column['assessment'].'-'.$column['category'].'-'.$column['field'].'-'.$row['gibbonPersonID'];
                                if (isset($externalResults[$externalIndex])) {
                                    $excel->getActiveSheet()->setCellValue(num2alpha($column['count'] + 6).$activeRow, $externalResults[$externalIndex]);
                                }
                            } else { //Output internal assessment data

                                $internalIndex = $column['gibbonYearGroupID'].'-'.$column['course'].'-'.$column['type'].'-'.$row['gibbonPersonID'].'-'.$column['assessment'];
                                if (isset($internalResults[$internalIndex])) {
                                    $excel->getActiveSheet()->setCellValue(num2alpha($column['count'] + 6).$activeRow, $internalResults[$internalIndex]);
                                }
                            }
                        }
                        ++$activeRow;
                    }
                }
            }
        }
    }

    //FINALISE THE DOCUMENT SO IT IS READY FOR DOWNLOAD
    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $excel->setActiveSheetIndex(0);

    // Redirect output to a client’s web browser (Excel2007)
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="Data Points.xlsx"');
    header('Cache-Control: max-age=0');
    // If you're serving to IE 9, then the following may be needed
    header('Cache-Control: max-age=1');

    // If you're serving to IE over SSL, then the following may be needed
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
    header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
    header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    header('Pragma: public'); // HTTP/1.0

    $objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($excel, 'Xlsx');
    $objWriter->save('php://output');
    exit;
}
