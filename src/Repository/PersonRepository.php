<?php
/**
 * Created by PhpStorm.
 *
 * Gibbon-Responsive
 *
 * (c) 2018 Craig Rayner <craig@craigrayner.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * UserProvider: craig
 * Date: 23/11/2018
 * Time: 09:01
 */
namespace App\Repository;

use App\Entity\Person;
use App\Entity\SchoolYear;
use App\Provider\ProviderFactory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class PersonRepository
 * @package App\Repository
 */
class PersonRepository extends ServiceEntityRepository
{
    /**
     * PersonRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Person::class);
    }

    /**
     * Loads the user for the given username.
     *
     * This method must return null if the user is not found.
     *
     * @param string $username The username
     *
     * @return UserInterface|null
     */
    public function loadUserByUsernameOrEmail($username)
    {
        return $this->createQueryBuilder('p')
            ->where('p.email = :email OR p.username = :username')
            ->setParameter('email', $username)
            ->setParameter('username', $username)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * findStaffForFastFinder
     * @param string $staffTitle
     * @return array|null
     * @throws \Exception
     */
    public function findStaffForFastFinder(string $staffTitle): ?array
    {
        return $this->createQueryBuilder('p')
            ->select(["CONCAT('".$staffTitle . ' - ' . "', p.surname, ', ', p.preferredName) as text", "CONCAT('Sta-', p.id) AS id", "CONCAT(p.username, ' ', p.email) AS search"])
            ->join('p.staff', 's')
            ->where('p.status = :full')
            ->andWhere('s.person IS NOT NULL')
            ->andWhere('(p.dateStart IS NULL OR p.dateStart <= :today)')
            ->andWhere('(p.dateEnd IS NULL OR p.dateEnd >= :today)')
            ->setParameters(['full' => 'Full', 'today' => new \DateTime(date('Y-m-d'))])
            ->orderBy('text')
            ->getQuery()
            ->getResult();
    }

    public function findStudentsForFastFinder(SchoolYear $schoolYear, string $studentTitle): ?array
    {
        return $this->createQueryBuilder('p')
            ->select([
                "CONCAT('".$studentTitle."', p.surname, ', ', p.preferredName, ' (', rg.name, ', ', p.studentID, ')') AS text",
                "CONCAT(p.username, ' ', p.firstName, ' ', p.email) AS search",
                "CONCAT('Stu-', p.id) AS id",
            ])
            ->join('p.studentEnrolments', 'se')
            ->join('se.rollGroup', 'rg')
            ->where('se.schoolYear = :schoolYear')
            ->andWhere('p.status = :full')
            ->andWhere('(p.dateStart IS NULL OR p.dateStart <= :today)')
            ->andWhere('(p.dateEnd IS NULL OR p.dateEnd >= :today)')
            ->setParameters(['today' => new \DateTime(date('Y-m-d')), 'schoolYear' => $schoolYear, 'full' => 'Full'])
            ->orderBy('text')
            ->getQuery()
            ->getResult();

        /*
         *             $sql = "SELECT gibbonPerson.gibbonPersonID AS id,
                    (CASE WHEN gibbonPerson.username LIKE :search THEN concat(surname, ', ', preferredName, ' (', gibbonRollGroup.name, ', ', gibbonPerson.username, ')')
                        WHEN gibbonPerson.studentID LIKE :search THEN concat(surname, ', ', preferredName, ' (', gibbonRollGroup.name, ', ', gibbonPerson.studentID, ')')
                        WHEN gibbonPerson.firstName LIKE :search AND firstName<>preferredName THEN concat(surname, ', ', firstName, ' \"', preferredName, '\" (', gibbonRollGroup.name, ')' )
                        ELSE concat(surname, ', ', preferredName, ' (', gibbonRollGroup.name, ')') END) AS name,
                    NULL as type
                    FROM gibbonPerson, gibbonStudentEnrolment, gibbonRollGroup
                    WHERE gibbonPerson.gibbonPersonID=gibbonStudentEnrolment.gibbonPersonID
                    AND gibbonStudentEnrolment.gibbonRollGroupID=gibbonRollGroup.gibbonRollGroupID
                    AND status='Full'";
*/

    }
}
