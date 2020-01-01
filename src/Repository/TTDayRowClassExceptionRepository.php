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
 * Time: 17:07
 */
namespace App\Repository;

use App\Entity\TTDayRowClassException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class TTDayRowClassExceptionRepository
 * @package App\Repository
 */
class TTDayRowClassExceptionRepository extends ServiceEntityRepository
{
    /**
     * TTDayRowClassExceptionRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TTDayRowClassException::class);
    }
}
