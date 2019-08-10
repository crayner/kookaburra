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

use App\Entity\CourseClassPerson;
use App\Entity\Person;
use App\Entity\SchoolYear;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class CourseClassPersonRepository
 * @package App\Repository
 */
class CourseClassPersonRepository extends ServiceEntityRepository
{
    /**
     * CourseClassPersonRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CourseClassPerson::class);
    }

    /**
     * findAccessibleClasses
     * @param SchoolYear $schoolYear
     * @param Person $person
     * @return mixed
     */
    public function findAccessibleClasses(SchoolYear $schoolYear, Person $person, string $classTitle)
    {
        $result = $this->createQueryBuilder('ccp')
            ->select([
                "CONCAT('Cla-', cc.id) AS id",
                "CONCAT('" . $classTitle . "', c.nameShort, '.', cc.nameShort) AS text",
                'c.name AS search'
            ])
            ->join('ccp.courseClass', 'cc')
            ->join('cc.course', 'c')
            ->where('c.schoolYear = :schoolYear')
            ->andWhere('ccp.person = :person')
            ->orderBy('text')
            ->setParameters(['schoolYear' => $schoolYear, 'person' => $person])
            ->getQuery()
            ->getResult();
        return $result;
    }

    /**
     * getMyClasses
     * @param SchoolYear $schoolYear
     * @param Person $person
     * @return mixed
     */
    public function getMyClasses(SchoolYear $schoolYear, Person $person)
    {
        return $this->createQueryBuilder('ccp')
            ->select(['c.nameShort AS course', 'cc.nameShort AS class', 'cc.id as gibbonCourseClassID'])
            ->leftJoin('ccp.courseClass', 'cc')
            ->leftJoin('cc.course', 'c')
            ->where('c.schoolYear = :schoolYear')
            ->setParameter('schoolYear', $schoolYear)
            ->andWhere('ccp.person = :person')
            ->setParameter('person', $person)
            ->andWhere('ccp.role NOT LIKE :role')
            ->setParameter('role', '% - Left%')
            ->orderBy('c.name', 'ASC')
            ->addOrderBy('cc.name', 'ASC')
            ->getQuery()
            ->getResult();
/*
        try {
            $data = array('gibbonSchoolYearID' => $_SESSION[$guid]['gibbonSchoolYearID'], 'gibbonPersonID' => $_SESSION[$guid]['gibbonPersonID']);
            $sql = 'SELECT gibbonCourse.nameShort AS course, gibbonCourseClass.nameShort AS class, gibbonCourseClass.gibbonCourseClassID FROM gibbonCourse, gibbonCourseClass, gibbonCourseClassPerson
WHERE gibbonSchoolYearID=:gibbonSchoolYearID
AND gibbonCourse.gibbonCourseID=gibbonCourseClass.gibbonCourseID
AND gibbonCourseClass.gibbonCourseClassID=gibbonCourseClassPerson.gibbonCourseClassID
AND gibbonCourseClassPerson.gibbonPersonID=:gibbonPersonID
AND NOT role LIKE \'% - Left%\' ORDER BY course, class';
            $result = $connection2->prepare($sql);
            $result->execute($data);
        } catch (PDOException $e) {
            echo "<div class='error'>" . $e->getMessage() . '</div>';
        }
*/
    }
}
