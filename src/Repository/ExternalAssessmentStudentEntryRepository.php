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

use App\Entity\ExternalAssessmentStudentEntry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class ExternalAssessmentStudentEntryRepository
 * @package App\Repository
 */
class ExternalAssessmentStudentEntryRepository extends ServiceEntityRepository
{
    /**
     * ExternalAssessmentStudentEntryRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ExternalAssessmentStudentEntry::class);
    }
}
