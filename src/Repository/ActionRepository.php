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
use App\Entity\Module;
use App\Exception\Exception;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
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
        if ('' === $moduleName)
            return [];
        return $this->createQueryBuilder('a')
            ->leftJoin('a.module', 'm')
            ->leftJoin('a.permissions', 'p')
            ->where('a.URLList = :urlList')
            ->andWhere('p.role = :roleID')
            ->andWhere('m.name = :moduleName')
            ->setParameters(['urlList' => $URLList, 'moduleName' => $moduleName, 'roleID' => $roleID])
            ->getQuery()
            ->getArrayResult();
    }

    /**
     * findOneByModuleContainsURL
     * @param Module $module
     * @param string $address
     * @return Action|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneByModuleContainsURL(Module $module, string $address): ?Action
    {
        return $this->createQueryBuilder('a')
            ->where('a.module = :module')
            ->setParameter('module', $module)
            ->andWhere('a.URLList LIKE :route')
            ->setParameter('route', '%' . $address . '%')
            ->orderBy('a.precedence', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * findOneByNameModule
     * @param string $name
     * @param Module $module
     * @return Action
     * @throws NonUniqueResultException
     */
    public function findOneByNameModule(string $name, Module $module): Action
    {
        return $this->createQueryBuilder('a')
            ->where('a.name = :name')
            ->andWhere('a.module = :module')
            ->setParameters(['name' => $name, 'module' => $module])
            ->orderBy('a.precedence', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
