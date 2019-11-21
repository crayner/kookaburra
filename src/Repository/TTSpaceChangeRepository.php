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
 * Time: 17:26
 */
namespace App\Repository;

use App\Entity\TTSpaceChange;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class TTSpaceChangeRepository
 * @package App\Repository
 */
class TTSpaceChangeRepository extends ServiceEntityRepository
{
    /**
     * TTSpaceChangeRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TTSpaceChange::class);
    }
}
