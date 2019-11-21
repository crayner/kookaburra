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

use App\Entity\Space;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Connection;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class SpaceRepository
 * @package App\Repository
 */
class SpaceRepository extends ServiceEntityRepository
{
    /**
     * ApplicationFormRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Space::class);
    }

    /**
     * findAllIn
     * @param $spaces
     * Array of Space ID's
     * @return array
     */
    public function findAllIn($spaces): array
    {
        return $this->createQueryBuilder('s')
            ->where('s.id IN (:spaces)')
            ->setParameter('spaces', $spaces, Connection::PARAM_INT_ARRAY)
            ->orderBy('s.name')
            ->getQuery()
            ->getResult();
    }
}
