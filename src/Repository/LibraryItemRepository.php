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

use App\Entity\LibraryItem;
use App\Entity\LibraryType;
use App\Entity\Person;
use App\Entity\Space;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Connection;
use Kookaburra\Library\Entity\CatalogueSearch;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class LibraryItemRepository
 * @package App\Repository
 */
class LibraryItemRepository extends ServiceEntityRepository
{
    /**
     * ApplicationFormRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, LibraryItem::class);
    }

    /**
     * findAllIn
     * @param $items
     * Array of item ID's
     * @return array
     */
    public function findAllIn($items): array
    {
        return $this->createQueryBuilder('s')
            ->where('s.id IN (:items)')
            ->setParameter('items', $items, Connection::PARAM_INT_ARRAY)
            ->orderBy('s.name')
            ->getQuery()
            ->getResult();
    }

    public function findBySearch(CatalogueSearch $search)
    {
        $query = $this->createQueryBuilder('li')
            ->where('(li.identifier LIKE :search OR li.name LIKE :search OR li.producer LIKE :search)')
            ->setParameter('search', '%'.$search->getSearch().'%')
            ->andWhere('(li.fields LIKE :searchField)')
            ->setParameter('searchField', '%'.$search->getSearchFields().'%');

        if ($search->getType() instanceof LibraryType)
            $query->andWhere('li.libraryType = :libraryType')
                ->setParameter('libraryType', $search->getType());

        if ($search->getLocation() instanceof Space)
            $query->andWhere('li.space = :space')
                ->setParameter('space', $search->getLocation());

        if ($search->getPerson() instanceof Person)
            $query->andWhere('li.ownership = :person OR li')
                ->setParameter('person', $search->getPerson());

        return $query->orderBy('li.identifier','ASC')->getQuery()
            ->getResult();
    }
}
