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
 * Time: 21:56
 */
namespace App\Repository;

use App\Entity\UnitBlock;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class UnitBlockRepository
 * @package App\Repository
 */
class UnitBlockRepository extends ServiceEntityRepository
{
    /**
     * UnitBlockRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UnitBlock::class);
    }
}
