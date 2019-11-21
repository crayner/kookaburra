<?php
/**
 * Created by PhpStorm.
 *
 * Kookaburra
 *
 * (c) 2018 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 23/11/2018
 * Time: 15:27
 */
namespace App\Repository;

use App\Entity\INPersonDescriptor;
use Kookaburra\UserAdmin\Entity\Person;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class INPersonDescriptorRepository
 * @package App\Repository
 */
class INPersonDescriptorRepository extends ServiceEntityRepository
{
    /**
     * ApplicationFormRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, INPersonDescriptor::class);
    }

    /**
     * findAlertsByPerson
     * @param Person $person
     * @return array|null
     */
    public function findAlertsByPerson(Person $person): ?array
    {
        return $this->createQueryBuilder('i')
            ->join('i.alertLevel', 'al')
            ->where('i.person = :person')
            ->setParameter('person', $person)
            ->orderBy('al.sequenceNumber', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
