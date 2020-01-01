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
 * Time: 17:00
 */
namespace App\Repository;

use App\Entity\TTDay;
use App\Entity\TTDayRowClass;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class TTDayRowClassRepository
 * @package App\Repository
 */
class TTDayRowClassRepository extends ServiceEntityRepository
{
    /**
     * TTDayRowClassRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TTDayRowClass::class);
    }

    /**
     * findByTTDay
     * @param TTDay $day
     * @return mixed
     */
    public function findByTTDay(TTDay $day)
    {
        return $this->createQueryBuilder('tdrc')
            ->where('tdrc.TTDay = :day')
            ->setParameter('day', $day)
            ->getQuery()
            ->getResult();
    }
}
