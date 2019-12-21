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

use App\Entity\MarkbookEntry;
use Kookaburra\UserAdmin\Entity\Person;
use Kookaburra\SchoolAdmin\Entity\AcademicYear;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class MarkbookEntryRepository
 * @package App\Repository
 */
class MarkbookEntryRepository extends ServiceEntityRepository
{
    /**
     * ApplicationFormRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MarkbookEntry::class);
    }

    /**
     * findAttainmentOrEffortConcerns
     * @param Person $person
     * @param AcademicYear $schoolYear
     * @return mixed
     * @throws \Exception
     */
    public function findAttainmentOrEffortConcerns(Person $person, AcademicYear $schoolYear)
    {
        return $this->createQueryBuilder('me')
            ->join('me.markbookColumn', 'mc')
            ->join('mc.courseClass', 'cc')
            ->join('cc.course', 'c')
            ->where('me.student = :person')
            ->andWhere('(me.attainmentConcern = :yes OR me.effortConcern = :yes)')
            ->andWhere('mc.complete = :yes')
            ->andWhere('c.academicYear = :academicYear')
            ->andWhere('mc.completeDate <= :today')
            ->andWhere('mc.completeDate > :date')
            ->setParameter('person', $person)
            ->setParameter('yes', 'Y')
            ->setParameter('academicYear', $schoolYear)
            ->setParameter('today', new \DateTime(date('Y-m-d'))) // today
            ->setParameter('date', new \DateTime(date('Y-m-d', strtotime('-60 day')))) // 60 days ago
            ->getQuery()
            ->getResult();
    }

}
