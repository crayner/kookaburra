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
use App\Entity\SchoolYear;
use App\Util\SchoolYearHelper;
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
            ->andWhere('c.schoolYear = :schoolYear')
            ->setParameter('schoolYear', SchoolYearHelper::getCurrentSchoolYear())
            ->getQuery()
            ->getResult();
    }

    /**
     * findAccessibleClasses
     * @param SchoolYear $schoolYear
     * @return mixed
     */
    public function findAccessibleClasses(SchoolYear $schoolYear, string $classTitle)
    {
        $result = $this->createQueryBuilder('cc')
            ->select([
                "CONCAT('Cla-', cc.id) As id",
                "CONCAT('" . $classTitle . "', c.nameShort, '.', cc.nameShort) AS text",
                'c.name AS search'
            ])
            ->join('cc.course', 'c')
            ->where('c.schoolYear = :schoolYear')
            ->setParameter('schoolYear', $schoolYear)
            ->orderBy('text')
            ->getQuery()
            ->getResult();
        return $result;
    }

    /**
     * findByPersonSchoolYear
     * @param SchoolYear $schoolYear
     * @param Person $person
     * @return mixed
     */
    public function findByPersonSchoolYear(SchoolYear $schoolYear, Person $person)
    {
        return $this->createQueryBuilder('cc')
            ->distinct()
            ->leftJoin('cc.course', 'c')
            ->leftjoin('cc.courseClassPeople', 'ccp', 'with', 'ccp.person = :person')
            ->where('c.schoolYear = :schoolYear')
            ->setParameter('schoolYear', $schoolYear)
            ->setParameter('person', $person)
            ->andWhere('ccp.role NOT LIKE :role')
            ->setParameter('role', '% - Left%')
            ->orderBy('c.nameShort', 'ASC')
            ->addOrderBy('cc.nameShort', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
