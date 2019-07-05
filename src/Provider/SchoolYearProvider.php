<?php
/**
 * Created by PhpStorm.
 *
 * bilby
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 5/07/2019
 * Time: 11:35
 */

namespace App\Provider;

use App\Entity\SchoolYear;
use App\Manager\Traits\EntityTrait;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SchoolYearProvider implements EntityProviderInterface
{
    use EntityTrait;

    /**
     * @var string
     */
    private $entityName = SchoolYear::class;


    /**
     * setCurrentSchoolYear
     * @param SessionInterface $session
     * @return object|null
     * @throws \Exception
     */
    public function setCurrentSchoolYear(SessionInterface $session)
    {
        $row =$this->getRepository()->findOneBy(['status' => 'Current']);


        //Check number of rows returned.
        if (!$row instanceof SchoolYear) {
            die(__('Configuration Error: there is a problem accessing the current Academic Year from the database.'));
        }
        
        $session->set('gibbonSchoolYearID', $row->getId());
        $session->set('gibbonSchoolYearName', $row->getName());
        $session->set('gibbonSchoolYearSequenceNumber', $row->getSequenceNumber());
        $session->set('gibbonSchoolYearFirstDay', $row->getFirstDay());
        $session->set('gibbonSchoolYearLastDay', $row->getLastDay());
        return $row;
    }

}