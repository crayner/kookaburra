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
 * Time: 22:09
 */
namespace App\Repository;

use App\Entity\UnitClassBlock;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class UnitClassBlockRepository
 * @package App\Repository
 */
class UnitClassBlockRepository extends ServiceEntityRepository
{
    /**
     * UnitClassBlockRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UnitClassBlock::class);
    }
}
