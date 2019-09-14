<?php
/**
 * Created by PhpStorm.
 *
 * Gibbon-Responsive
 *
 * (c) 2018 Craig Rayner <craig@craigrayner.com>
 *
 * UserProvider: craig
 * Date: 24/11/2018
 * Time: 16:17
 */
namespace App\Repository;

use App\Entity\StringReplacement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class StringReplacementRepository
 * @package App\Repository
 */
class StringReplacementRepository extends ServiceEntityRepository
{
    /**
     * StringReplacementRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, StringReplacement::class);
    }

    /**
     * getPaginationSearch
     * @param string $search
     * @return mixed
     */
    public function getPaginationSearch(string $search)
    {
        $search = '%' . $search . '%';
        return $this->createQueryBuilder('s')
            ->where('s.original LIKE :search')
            ->orWhere('s.replacement LIKE :search')
            ->setParameter('search', $search)
            ->orderBy('s.original')
            ->getQuery()
            ->getResult();
    }
}
