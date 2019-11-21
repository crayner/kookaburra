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
 * Time: 22:14
 */
namespace App\Repository;

use App\Entity\UnitOutcome;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class UnitOutcomeRepository
 * @package App\Repository
 */
class UnitOutcomeRepository extends ServiceEntityRepository
{
    /**
     * UnitOutcomeRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UnitOutcome::class);
    }
}
