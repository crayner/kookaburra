<?php
/**
 * Created by PhpStorm.
 *
 * Gibbon-Responsive
 *
 * (c) 2018 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 23/11/2018
 * Time: 15:27
 */
namespace App\Repository;

use App\Entity\Course;
use App\Entity\Department;
use App\Entity\Person;
use App\Entity\SchoolYear;
use App\Util\SchoolYearHelper;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class CourseRepository
 * @package App\Repository
 */
class CourseRepository extends ServiceEntityRepository
{
    /**
     * CourseRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Course::class);
    }

    /**
     * @return array
     */
    public function findByPerson(Person $person): array
    {
        return $this->createQueryBuilder('c')
            ->select('DISTINCT c')
            ->leftJoin('c.courseClasses', 'cc')
            ->leftJoin('cc.courseClassPeople', 'ccp')
            ->where('ccp.person = :person')
            ->setParameter('person', $person)
            ->andWhere('c.schoolYear = :schoolYear')
            ->setParameter('schoolYear', SchoolYearHelper::getCurrentSchoolYear())
            ->getQuery()
            ->getResult();
    }

    /**
     * findByDepartment
     * @param Department $department
     * @return array
     */
    public function findByDepartment(Department $department, SchoolYear $schoolYear): array
    {
        return $this->createQueryBuilder('c')
            ->join('c.courseClasses', 'cc')
            ->where('c.department = :department')
            ->setParameter('department', $department)
            ->andWhere('c.yearGroupList != :empty')
            ->setParameter('empty', '')
            ->andWhere('c.schoolYear = :schoolYear')
            ->setParameter('schoolYear', $schoolYear)
            ->groupBy('c.id')
            ->orderBy('c.nameShort', 'ASC')
            ->addOrderBy('c.name', 'ASC')
            ->getQuery()
            ->getResult();
    /*
                    $sqlCourse = "SELECT gibbonCourse.* FROM gibbonCourse
                        JOIN gibbonCourseClass ON (gibbonCourseClass.gibbonCourseID=gibbonCourse.gibbonCourseID)
                        WHERE gibbonDepartmentID=:gibbonDepartmentID
                        AND gibbonYearGroupIDList <> ''
                        AND gibbonSchoolYearID=(SELECT gibbonSchoolYearID FROM gibbonSchoolYear WHERE status='Current')
                        GROUP BY gibbonCourse.gibbonCourseID
                        ORDER BY nameShort, name";
    */
    }
}
