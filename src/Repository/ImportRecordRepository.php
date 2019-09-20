<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 20/09/2019
 * Time: 14:02
 */

namespace App\Repository;

use App\Entity\ImportRecord;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class ImportRecordRepository
 * @package App\Repository
 */
class ImportRecordRepository extends ServiceEntityRepository
{
    /**
     * GroupRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ImportRecord::class);
    }

    /**
     * findLastModifiedByName
     * @param $name
     * @return ImportRecord|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findLastModifiedByName($name): ?ImportRecord
    {
        return $this->createQueryBuilder('ir')
            ->where('ir.importType = :name')
            ->setParameter('name', $name)
            ->orderBy('ir.lastModified', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}