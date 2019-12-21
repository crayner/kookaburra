<?php
/**
 * Created by PhpStorm.
 *
 * Kookaburra
 *
 * (c) 2018 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 23/11/2018
 * Time: 15:27
 */
namespace App\Repository;

use App\Entity\CourseClass;
use Kookaburra\UserAdmin\Entity\Person;
use Kookaburra\SchoolAdmin\Entity\AcademicYear;
use Kookaburra\SchoolAdmin\Util\AcademicYearHelper;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class CourseClassRepository
 * @package App\Repository
 */
class CourseClassRepository extends ServiceEntityRepository
{
    /**
     * CourseClassRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CourseClass::class);
    }

    /**
     * @param Person $person
     * @return array
     */
    public function findByPerson(Person $person): array
    {
        return $this->createQueryBuilder('cc')
            ->select('DISTINCT cc')
            ->leftJoin('cc.courseClassPeople', 'ccp')
            ->leftJoin('cc.course', 'c')
            ->where('ccp.person = :person')
            ->setParameter('person', $person)
            ->andWhere('c.academicYear = :academicYear')
            ->setParameter('academicYear', AcademicYearHelper::getCurrentSchoolYear())
            ->getQuery()
            ->getResult();
    }

    /**
     * findAccessibleClasses
     * @param AcademicYear $schoolYear
     * @return mixed
     */
    public function findAccessibleClasses(AcademicYear $schoolYear, string $classTitle)
    {
        $result = $this->createQueryBuilder('cc')
            ->select([
                "CONCAT('Cla-', cc.id) As id",
                "CONCAT('" . $classTitle . "', c.nameShort, '.', cc.nameShort) AS text",
                'c.name AS search'
            ])
            ->join('cc.course', 'c')
            ->where('c.academicYear = :academicYear')
            ->setParameter('academicYear', $schoolYear)
            ->orderBy('text')
            ->getQuery()
            ->getResult();
        return $result;
    }

    /**
     * findByPersonSchoolYear
     * @param AcademicYear $schoolYear
     * @param Person $person
     * @return mixed
     */
    public function findByPersonSchoolYear(AcademicYear $schoolYear, Person $person)
    {
        return $this->createQueryBuilder('cc')
            ->distinct()
            ->leftJoin('cc.course', 'c')
            ->leftjoin('cc.courseClassPeople', 'ccp', 'with', 'ccp.person = :person')
            ->where('c.academicYear = :academicYear')
            ->setParameter('academicYear', $schoolYear)
            ->setParameter('person', $person)
            ->andWhere('ccp.role NOT LIKE :role')
            ->setParameter('role', '% - Left%')
            ->orderBy('c.nameShort', 'ASC')
            ->addOrderBy('cc.nameShort', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
