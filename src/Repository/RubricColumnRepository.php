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

use App\Entity\RubricColumn;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;
use Kookaburra\SchoolAdmin\Entity\ScaleGrade;

/**
 * Class RubricColumnRepository
 * @package App\Repository
 */
class RubricColumnRepository extends ServiceEntityRepository
{
    /**
     * ApplicationFormRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RubricColumn::class);
    }

    /**
     * countGradeUse
     * @param ScaleGrade $grade
     * @return int
     */
    public function countGradeUse(ScaleGrade $grade): int
    {
        try {
            return intval($this->createQueryBuilder('c')
                ->where('c.scaleGrade = :grade')
                ->setParameter('grade', $grade)
                ->select(['COUNT(c.id)'])
                ->getQuery()
                ->getSingleScalarResult());
        } catch (NoResultException | NonUniqueResultException $e) {
            return 0;
        }
    }
}
