<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * User: craig
 * Date: 20/09/2019
 * Time: 14:02
 */

namespace App\Repository;

use App\Entity\ImportHistory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class ImportHistoryRepository
 * @package App\Repository
 */
class ImportHistoryRepository extends ServiceEntityRepository
{
    /**
     * GroupRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ImportHistory::class);
    }

    /**
     * findLastModifiedByName
     * @param $name
     * @return ImportHistory|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findLastModifiedByName($name): ?ImportHistory
    {
        return $this->createQueryBuilder('ir')
            ->where('ir.importType = :name')
            ->setParameter('name', $name)
            ->orderBy('ir.lastModified', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}