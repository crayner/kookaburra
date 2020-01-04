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

use App\Entity\ActivitySlot;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;
use Kookaburra\SchoolAdmin\Entity\Facility;

/**
 * Class ActivitySlotRepository
 * @package App\Repository
 */
class ActivitySlotRepository extends ServiceEntityRepository
{
    /**
     * ActivitySlotRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ActivitySlot::class);
    }

    /**
     * countFacility
     * @param Facility $facility
     * @return int
     */
    public function countFacility(Facility $facility): int
    {
        try {
            return intval($this->createQueryBuilder('a')
                ->select('COUNT(a.id)')
                ->where('a.facility = :facility')
                ->setParameter('facility', $facility)
                ->getQuery()
                ->getSingleScalarResult());
        } catch ( NoResultException | NonUniqueResultException $e) {
            return 0;
        }
    }
}
