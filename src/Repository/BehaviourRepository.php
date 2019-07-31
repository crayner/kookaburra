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

use App\Entity\Behaviour;
use App\Entity\Person;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class BehaviourRepository
 * @package App\Repository
 */
class BehaviourRepository extends ServiceEntityRepository
{
    /**
     * BehaviourRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Behaviour::class);
    }

    public function findNegativeInLast60Days(Person $person): ?array
    {
        return $this->createQueryBuilder('b')
            ->where('b.person = :person')
            ->andWhere('b.type = :negative')
            ->andWhere('b.date > :date')
            ->setParameter('date', new \DateTime('-60 days'))
            ->setParameter('negative', 'Negative')
            ->setParameter('person', $person)
            ->getQuery()
            ->getResult();
    }
}
