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

use App\Entity\PersonField;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class PersonFieldRepository
 * @package App\Repository
 */
class PersonFieldRepository extends ServiceEntityRepository
{
    /**
     * ApplicationFormRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PersonField::class);
    }

    /**
     * getCustomFields
     * @param array $where
     * @param array $data
     * @return array
     */
    public function getCustomFields(array $where, array $data): array
    {
        $query = $this->createQueryBuilder('pf')
            ->where('pf.active = :yes');

        foreach($where as $search)
            $query = $query->andWhere($search);

        $query = $query->setParameters($data)
            ->setParameter('yes', 'Y')
            ->getQuery();

        return $query
            ->getResult();
    }
}
