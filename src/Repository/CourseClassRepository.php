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

use App\Entity\CourseClass;
use App\Entity\Person;
use App\Entity\SchoolYear;
use App\Util\SchoolYearHelper;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class CourseClassRepository
 * @package App\Repository
 */
class CourseClassRepository extends ServiceEntityRepository
{
    /**
     * CourseClassRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
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
    public function findAccessibleClasses(SchoolYear $schoolYear)
    {
        $result = $this->createQueryBuilder('cc')
            ->select(['cc.id', "CONCAT(c.nameShort, '.', cc.nameShort, ': ', c.name) AS name"])
            ->join('cc.course', 'c')
            ->where('c.schoolYear = :schoolYear')
            ->setParameter('schoolYear', $schoolYear)
            ->getQuery()
            ->getResult();
        foreach($result as $q=>$w)
            $result[$q]['type'] = NULL;
        return $result;
    }
}
