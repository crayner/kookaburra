<?php
/**
 * Created by PhpStorm.
 *
 * Kookaburra
 *
 * (c) 2018 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 5/12/2018
 * Time: 16:10
 */
namespace App\Repository;

use Kookaburra\UserAdmin\Entity\Person;
use App\Entity\RollGroup;
use App\Entity\StudentEnrolment;
use Kookaburra\SchoolAdmin\Util\AcademicYearHelper;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class StudentEnrolmentRepository
 * @package App\Repository
 */
class StudentEnrolmentRepository extends ServiceEntityRepository
{
    /**
     * StudentEnrolmentRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StudentEnrolment::class);
    }

    /**
     * @param Person $person
     * @return array
     */
    public function findStaffYearGroupsByRollGroup(Person $person): array
    {
        $x = $this->createQueryBuilder('se')
            ->select('DISTINCT yg.id AS yearGroupList')
            ->leftJoin('se.yearGroup', 'yg')
            ->leftJoin('se.rollGroup', 'rg')
            ->where('rg.tutor = :person OR rg.tutor2 = :person OR rg.tutor3 = :person')
            ->setParameter('person', $person)
            ->andWhere('se.academicYear = :academicYear')
            ->setParameter('academicYear', AcademicYearHelper::getCurrentSchoolYear())
            ->getQuery()
            ->getResult();
        $results = [];
        foreach($x as $list)
            $results = array_merge($results, [str_pad($list['yearGroupList'],3, '0', STR_PAD_LEFT)]);

        return array_unique($results);
    }

    /**
     * @param Person $person
     * @return array
     */
    public function findStudentYearGroup(Person $person): array
    {
        $x = $this->createQueryBuilder('se')
            ->select('DISTINCT yg.id AS yearGroupList')
            ->leftJoin('se.yearGroup', 'yg')
            ->where('se.academicYear = :academicYear')
            ->andWhere('se.person = :person')
            ->setParameter('academicYear', AcademicYearHelper::getCurrentSchoolYear())
            ->setParameter('person', $person)
            ->getQuery()
            ->getResult();
        $results = [];
        foreach($x as $list)
            $results = array_merge($results, explode(',',$list['yearGroupList']));

        return array_unique($results);
    }

    /**
     * getStudentEnrolmentCount
     * @param integer|null $AcademicYearID
     * @return int
     */
    public function getStudentEnrolmentCount(?int $schoolYearID = null): int
    {
        $x = $this->createQueryBuilder('se')
            ->select('COUNT(p.id)')
            ->join('se.person', 'p')
            ->join('se.rollGroup', 'rg')
            ->join('rg.academicYear', 'sy')
            ->where('sy.id = :sy_id')
            ->setParameter('sy_id', intval($schoolYearID))
            ->getQuery()
            ->getSingleScalarResult();

        return intval($x);
    }
}
