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
 * Time: 17:18
 */
namespace App\Repository;

use Kookaburra\Library\Entity\LibraryItem;
use Kookaburra\SchoolAdmin\Entity\Facility;
use Kookaburra\UserAdmin\Entity\Person;
use App\Entity\TTSpaceBooking;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class TTSpaceBookingRepository
 * @package App\Repository
 */
class TTSpaceBookingRepository extends ServiceEntityRepository
{
    /**
     * TTSpaceBookingRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TTSpaceBooking::class);
    }

    /**
     * findByDatePerson
     * @param EntityManagerInterface $em
     * @param \DateTime $date
     * @param Person|null $person
     * @return array|null
     */
    public function findByDatePerson(\DateTime $date, ?Person $person = null): ?array
    {
        $x = $this->createQueryBuilder('tsb')
            ->where('tsb.date LIKE :date')
            ->setParameter('date', $date->format('Y-m-d').'%')
        ;

        if ($person)
            $x->andWhere('tsb.person = :person')
                ->setParameter('person', $person);

        $result = $x
            ->orderBy('tsb.date', 'ASC')
            ->addOrderBy('tsb.timeStart', 'ASC')
            ->getQuery()
            ->getResult();
        $spaces = [];
        $libraryItems = [];
        foreach($result as $entity)
            if ($entity->getForeignKey() === 'gibbonSpaceID')
                $spaces[] = $entity->getForeignKeyID();
            else
                $libraryItems[] = $entity->getForeignKeyID();
        $spaces = $this->getEntityManager()->getRepository(Facility::class)->findAllIn($spaces);
        $libraryItems = $this->getEntityManager()->getRepository(LibraryItem::class)->findAllIn($libraryItems);
        foreach($result AS $booking)
            if ($entity->getForeignKey() === 'gibbonSpaceID')
                $entity->setSpace($this->filterEntity($spaces, $entity->getForeignKeyID()));
            else
                $entity->setLibraryItem($this->filterEntity($libraryItems, $entity->getForeignKeyID()));

        return $result;
    }

    /**
     * filterEntity
     * @param array $entities
     * @param int $id
     * @return bool|mixed
     */
    private function filterEntity(array $entities, int $id)
    {
        foreach($entities as $entity)
            if ($entity->getId() === $id)
                return $entity;
        return false;
    }
}
