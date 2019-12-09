<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 9/12/2019
 * Time: 11:31
 */

namespace App\Repository;

use App\Entity\StaffAbsenceType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class StaffAbsenceTypeRepository
 * @package App\Repository
 */
class StaffAbsenceTypeRepository extends ServiceEntityRepository
{
    /**
     * StaffAbsenceTypeRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StaffAbsenceType::class);
    }
}
