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
    public function findAccessibleClasses(SchoolYear $schoolYear, Person $person)
    {
        $result = $this->createQueryBuilder('ccp')
            ->select(['cc.id', "CONCAT(c.nameShort, '.', cc.nameShort, ': ', c.name) AS name"])
            ->join('ccp.courseClass', 'cc')
            ->join('cc.course', 'c')
            ->where('c.schoolYear = :schoolYear')
            ->andWhere('ccp.person = :person')
            ->orderBy('name')
            ->setParameters(['schoolYear' => $schoolYear, 'person' => $person])
            ->getQuery()
            ->getResult();
        foreach($result as $q=>$w)
            $result[$q]['type'] = NULL;
        return $result;
    }
}
