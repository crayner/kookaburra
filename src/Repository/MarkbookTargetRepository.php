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

use App\Entity\MarkbookTarget;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;
use Kookaburra\SchoolAdmin\Entity\ScaleGrade;

/**
 * Class MarkbookTargetRepository
 * @package App\Repository
 */
class MarkbookTargetRepository extends ServiceEntityRepository
{
    /**
     * ApplicationFormRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MarkbookTarget::class);
    }

    /**
     * countGradeUse
     * @param ScaleGrade $grade
     * @return int
     */
    public function countGradeUse(ScaleGrade $grade): int
    {
        try {
            return intval($this->createQueryBuilder('t')
                ->where('t.scaleGrade = :grade')
                ->setParameter('grade', $grade)
                ->select(['COUNT(t.id)'])
                ->getQuery()
                ->getSingleScalarResult());
        } catch (NoResultException | NonUniqueResultException $e) {
            return 0;
        }
    }
}
