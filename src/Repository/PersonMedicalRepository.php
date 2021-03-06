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

use Kookaburra\UserAdmin\Entity\Person;
use App\Entity\PersonMedical;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class PersonMedicalRepository
 * @package App\Repository
 */
class PersonMedicalRepository extends ServiceEntityRepository
{
    /**
     * ApplicationFormRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PersonMedical::class);
    }

    /**
     * findHighestMedicalRisk
     * @param Person $person
     * @return array|bool
     */
    function findHighestMedicalRisk(Person $person)
    {
        return $this->createQueryBuilder('pm')
            ->select(['al.id', 'al.name','al.colour','al.colourBG','al.nameShort'])
            ->join('pm.personMedicalConditions', 'pmc')
            ->join('pmc.alertLevel', 'al')
            ->where('pm.person = :person')
            ->orderBy('al.sequenceNumber', 'DESC')
            ->setParameter('person', $person)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
