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

use App\Entity\FamilyAdult;
use App\Entity\Person;
use App\Entity\SchoolYear;
use App\Provider\ProviderFactory;
use Kookaburra\UserAdmin\Util\UserHelper;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class FamilyAdultRepository
 * @package App\Repository
 */
class FamilyAdultRepository extends ServiceEntityRepository
{
    /**
     * FamilyAdultRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, FamilyAdult::class);
    }

    /**
     * @param Person $parent
     * @return array
     */
    public function findChildrenByParent(Person $parent): array
    {
        $x = $this->createQueryBuilder('fa')
            ->leftJoin('fa.family', 'f')
            ->leftJoin('f.children', 'fc')
            ->leftJoin('fc.person', 'p')
            ->select('fa,f,fc,p')
            ->where('fa.person = :person')
            ->setParameter('person', $parent)
            ->getQuery()
            ->getResult();
        $results = [];
        foreach(($x ?: []) as $item) {
            foreach($item->getFamily()->getChildren() as $child)
                if ($child->getPerson())
                    $results[$child->getPerson()->getId()] = $child->getPerson();
        }
        return $results;
    }

    /**
     * findStudentsOfParentFastFinder
     * @param Person $person
     * @param string $studentTitle
     * @return array
     */
    public function findStudentsOfParentFastFinder(Person $person, string $studentTitle, SchoolYear $schoolYear): ?array
    {
        $person = ProviderFactory::getRepository(Person::class)->find(2762);
        return $this->createQueryBuilder('fa')
            ->select([
                "CONCAT('".$studentTitle."', p.surname, ', ', p.preferredName, ' (', rg.name, ', ', p.studentID, ')') AS text",
                "CONCAT(p.username, ' ', p.firstName, ' ', p.email) AS search",
                "CONCAT('Stu-', p.id) AS id",
            ])
            ->leftJoin('fa.family', 'f')
            ->join('f.children', 'fc')
            ->join('fc.person', 'p')
            ->join('p.studentEnrolments', 'se')
            ->join('se.rollGroup', 'rg')
            ->where('fa.person = :person')
            ->andWhere('se.schoolYear = :schoolYear')
            ->andWhere('(p.dateStart IS NULL OR p.dateStart >= :today)')
            ->andWhere('(p.dateEnd IS NULL OR p.dateEnd <= :today)')
            ->setParameters(['person' => $person, 'schoolYear' => $schoolYear, 'today' => new \DateTime(date('Y-m-d'))])
            ->orderBy('text')
            ->getQuery()
            ->getResult();
    }
}
