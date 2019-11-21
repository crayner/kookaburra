<?php
/**
 * Created by PhpStorm.
 *
 * Kookaburra
 *
 * (c) 2018 Craig Rayner <craig@craigrayner.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * UserProvider: craig
 * Date: 23/11/2018
 * Time: 11:14
 */
namespace App\Repository;

use App\Entity\SchoolYear;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class SchoolYearRepository
 * @package App\Repository
 */
class SchoolYearRepository extends ServiceEntityRepository
{
    /**
     * SchoolYearRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SchoolYear::class);
    }

    /**
     * findAllByOverlap
     * @param SchoolYear $year
     * @return array
     */
    public function findAllByOverlap(SchoolYear $year): array
    {
        return $this->createQueryBuilder('s')
            ->where('s.id <> :year')
            ->setParameter('year', $year->getId())
            ->andwhere('(s.firstDay <= :firstDay AND s.lastDay >= :firstDay) OR (s.firstDay <= :lastDay AND s.lastDay >= :lastDay)')
            ->setParameter('firstDay', $year->getFirstDay())
            ->setParameter('lastDay', $year->getLastDay())
            ->getQuery()
            ->getResult();
    }
}
