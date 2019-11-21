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

use App\Entity\Country;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class CountryRepository
 * @package App\Repository
 */
class CountryRepository extends ServiceEntityRepository
{
    /**
     * CountryRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Country::class);
    }

    /**
     * getCountyCodeList
     */
    public function getCountryCodeList(): array
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.printable_name', 'ASC')
            ->addOrderBy('c.iddCountryCode', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
