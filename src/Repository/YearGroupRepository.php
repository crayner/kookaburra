<?php
/**
 * Created by PhpStorm.
 *
 * Gibbon-Responsive
 *
 * (c) 2018 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 5/12/2018
 * Time: 22:29
 */
namespace App\Repository;

use App\Entity\YearGroup;
use App\Util\SchoolYearHelper;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class YearGroupRepository
 * @package App\Repository
 */
class YearGroupRepository extends ServiceEntityRepository
{
    /**
     * YearGroupRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
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
}
