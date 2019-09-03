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
use App\Entity\CourseClassPerson;
use App\Entity\Person;
use App\Entity\SchoolYear;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class CourseClassPersonRepository
 * @package App\Repository
 */
class CourseClassPersonRepository extends ServiceEntityRepository
{
    /**
     * CourseClassPersonRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CourseClassPerson::class);
    }

    /**
     * findAccessibleClasses
     * @param SchoolYear $schoolYear
     * @param Person $person
     * @return mixed
     */
    public function findAccessibleClasses(SchoolYear $schoolYear, Person $person, string $classTitle)
    {
        $result = $this->createQueryBuilder('ccp')
            ->select([
                "CONCAT('Cla-', cc.id) AS id",
                "CONCAT('" . $classTitle . "', c.nameShort, '.', cc.nameShort) AS text",
                'c.name AS search'
            ])
            ->join('ccp.courseClass', 'cc')
            ->join('cc.course', 'c')
            ->where('c.schoolYear = :schoolYear')
            ->andWhere('ccp.person = :person')
            ->orderBy('text')
            ->setParameters(['schoolYear' => $schoolYear, 'person' => $person])
            ->getQuery()
            ->getResult();
        return $result;
    }

    /**
     * findStudentsInClass
     * @param CourseClass $class
     * @param SchoolYear $schoolYear
     * @param \DateTime $date
     * @return array
     */
    public function findStudentsInClass(CourseClass $class, SchoolYear $schoolYear, \DateTime $date): array
    {
        return $this->createQueryBuilder('ccp')
            ->select(['ccp.role','p.surname','p.preferredName','p.email','p.studentID','rg.nameShort AS rollGroup'])
            ->join('ccp.person', 'p')
            ->join('p.studentEnrolments', 'se')
            ->join('se.rollGroup', 'rg')
            ->where('ccp.courseClass = :courseClass')
            ->andWhere('p.status = :full')
            ->andWhere('(p.dateStart IS NULL OR p.dateStart <= :today)')
            ->andWhere('(p.dateEnd IS NULL OR p.dateEnd >= :today)')
            ->andWhere('se.schoolYear = :schoolYear')
            ->setParameter('courseClass', $class)
            ->setParameter('full', 'Full')
            ->setParameter('schoolYear', $schoolYear)
            ->setParameter('today', $date)
            ->orderBy('ccp.role')
            ->addOrderBy('p.surname')
            ->addOrderBy('p.preferredName')
            ->getQuery()
            ->getResult();
    }
}
