<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 10/08/2019
 * Time: 14:58
 */

namespace App\Provider;

use App\Entity\CourseClass;
use App\Entity\CourseClassPerson;
use App\Manager\Traits\EntityTrait;
use App\Util\SchoolYearHelper;

class CourseClassPersonProvider implements EntityProviderInterface
{
    use EntityTrait;

    /**
     * @var string
     */
    private $entityName = CourseClassPerson::class;

    /**
     * getClassPeopleList
     * @return array
     */
    public function getClassStudentList(CourseClass $class): array
    {
        $schoolYear = SchoolYearHelper::getCurrentSchoolYear();
        $date = new \DateTime(date('Y-m-d'));

        $list = $this->getRepository()->findStudentsInClass($class, $schoolYear, $date);

        return $list;
    }
}