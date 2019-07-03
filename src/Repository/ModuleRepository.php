<?php
/**
 * Created by PhpStorm.
 *
 * Gibbon-Responsive
 *
 * (c) 2018 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 23/11/2018
 * Time: 15:27
 */
namespace App\Repository;

use App\Entity\Module;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Connection;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class ModuleRepository
 * @package App\Repository
 */
class ModuleRepository extends ServiceEntityRepository
{
    /**
     * ApplicationFormRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Module::class);
    }

    /**
     * findModuleByRole
     * @param int $roleID
     * @return mixed
     */
    public function findModuleByRole(int $roleID)
    {
        return $this->createQueryBuilder('m')
            ->select(['m.category', 'm.name', 'm.type', 'm.entryURL', 'a.entryURL AS alternateEntryURL'])
            ->join('m.actions', 'a')
            ->join('a.permissions', 'p')
            ->join('p.role', 'r')
            ->where('m.active = :active')
            ->andWhere('a.menuShow = :active')
            ->andWhere('r.id = :role_id')
            ->groupBy('m.name')
            ->orderBy('m.name')
            ->addOrderBy('a.name')
            ->setParameter('active', 'Y')
            ->setParameter('role_id', intval($roleID))
            ->getQuery()
            ->getResult();
    }
}
