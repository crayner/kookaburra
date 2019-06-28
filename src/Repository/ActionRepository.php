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

use App\Entity\Action;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class ActionRepository
 * @package App\Repository
 */
class ActionRepository extends ServiceEntityRepository
{
    /**
     * ApplicationFormRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Action::class);
    }

    public function findOneByURLListModuleNameRoleID(string $URLList, string $moduleName, string $roleID = null)
    {
        return $this->createQueryBuilder('a')
            ->leftJoin('a.module', 'm')
            ->leftJoin('a.permissions', 'p')
            ->where('a.URLList = :urlList')
            ->andWhere('p.role = :roleID')
            ->andWhere('m.name = :moduleName')
            ->setParameters(['urlList' => $URLList, 'moduleName' => $moduleName, 'roleID' => $roleID])
            ->getQuery()
            ->getOneOrNullResult();
    }
}
