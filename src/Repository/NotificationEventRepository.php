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

use App\Entity\NotificationEvent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Kookaburra\SystemAdmin\Entity\Module;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class NotificationEventRepository
 * @package App\Repository
 */
class NotificationEventRepository extends ServiceEntityRepository
{
    /**
     * ApplicationFormRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, NotificationEvent::class);
    }

    /**
     * findAllNotificationEvents
     * @return array
     */
    public function findAllNotificationEvents(): array
    {
        return $this->createQueryBuilder('ne')
            ->join('ne.module', 'm')
            ->leftJoin('ne.listeners', 'nl')
            ->where('m.active = :yes')
            ->setParameter('yes', 'Y')
            ->groupBy('ne.id')
            ->orderBy('m.name')
            ->addOrderBy('ne.event')
            ->getQuery()
            ->getResult();
    }

    /**
     * deleteModuleRecords
     * @param Module $module
     * @return mixed
     */
    public function deleteModuleRecords(Module $module)
    {
        return $this->createQueryBuilder('ne')
            ->delete()
            ->where('ne.module = :module')
            ->setParameter('module', $module)
            ->getQuery()
            ->execute();
    }
}
