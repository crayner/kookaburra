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
 * Time: 22:29
 */
namespace App\Repository;

use App\Entity\YearGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Connection;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class YearGroupRepository
 * @package App\Repository
 */
class YearGroupRepository extends ServiceEntityRepository
{
    /**
     * YearGroupRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, YearGroup::class);
    }

    /**
     * findCurrentYearGroups
     * @return array
     */
    public function findCurrentYearGroups(): array
    {
        return $this->createQueryBuilder('yg')
            ->orderBy('yg.sequenceNumber')
            ->getQuery()
            ->getResult();
        ;
    }

    /**
     * findByYearGroupIDList
     * @param array $list
     * @param string $key
     * @return array
     */
    public function findByYearGroupIDList(array $list, string $key): array
    {
        return $this->createQueryBuilder('yg', 'yg.'.$key)
            ->where('yg.id in (:list)')
            ->select(['yg.id','yg.'.$key])
            ->setParameter('list', $list, Connection::PARAM_INT_ARRAY)
            ->getQuery()
            ->getArrayResult();
    }

    /**
     * findByYearGroupList
     * @param $list
     * @param $key
     * @return array
     */
    public function findByYearGroupList($list, $key): array
    {
        return $this->createQueryBuilder('yg', 'yg.id')
            ->where('yg.' . $key . ' in (:list)')
            ->select(['yg.id','yg.' . $key])
            ->setParameter('list', $list, Connection::PARAM_STR_ARRAY)
            ->getQuery()
            ->getArrayResult();
    }
}
