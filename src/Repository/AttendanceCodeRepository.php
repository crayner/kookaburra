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

use App\Entity\AttendanceCode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class AttendanceCodeRepository
 * @package App\Repository
 */
class AttendanceCodeRepository extends ServiceEntityRepository
{
    /**
     * AttendanceCodeRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AttendanceCode::class);
    }

    /**
     * findActive
     * @return array
     */
    public function findActive(bool $asArray = false): array
    {
        $query = $this->createQueryBuilder('a', 'a.id')
            ->where('a.active = :yes')
            ->setParameter('yes', 'Y')
            ->orderBy('a.sequenceNumber', 'ASC')
            ->getQuery();
        if ($asArray)
            return $query->getArrayResult();
        return $query->getResult();
    }

    /**
     * findDefaultAttendanceCode
     * @return AttendanceCode|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findDefaultAttendanceCode(): ?AttendanceCode
    {
        return $this->createQueryBuilder('ac')
            ->orderBy('ac.sequenceNumber', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
