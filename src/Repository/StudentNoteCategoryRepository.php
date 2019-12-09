<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 9/12/2019
 * Time: 12:05
 */

namespace App\Repository;

use App\Entity\StudentNoteCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class StudentNoteCategoryCategoryRepository
 * @package App\Repository
 */
class StudentNoteCategoryRepository extends ServiceEntityRepository
{
    /**
     * StudentNoteCategoryRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StudentNoteCategory::class);
    }
}
