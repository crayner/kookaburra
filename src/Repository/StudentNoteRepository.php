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
 * Time: 16:23
 */
namespace App\Repository;

use App\Entity\StudentNote;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class StudentNoteRepository
 * @package App\Repository
 */
class StudentNoteRepository extends ServiceEntityRepository
{
    /**
     * StudentNoteRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StudentNote::class);
    }
}
