<?php
/**
 * Created by PhpStorm.
 *
 * Kookaburra
 * (c) 2018 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 5/12/2018
 * Time: 16:11
 */
namespace App\Entity;

use App\Manager\Traits\BooleanList;
use Doctrine\ORM\Mapping as ORM;
use Kookaburra\RollGroups\Entity\RollGroup;
use Kookaburra\SchoolAdmin\Entity\AcademicYear;
use Kookaburra\SchoolAdmin\Entity\YearGroup;
use Kookaburra\UserAdmin\Entity\Person;

/**
 * Class StudentEnrolment
 * @package App\Entity
 * @ORM\Entity(repositoryClass="App\Repository\StudentEnrolmentRepository")
 * @ORM\Table(options={"auto_increment": 1}, name="StudentEnrolment", indexes={@ORM\Index(name="academic_year", columns={"academic_year"}), @ORM\Index(name="gibbonYearGroupID", columns={"gibbonYearGroupID"}), @ORM\Index(name="gibbonRollGroupID", columns={"gibbonRollGroupID"}), @ORM\Index(name="gibbonPersonIndex", columns={"gibbonPersonID","academic_year"})})
 */
class StudentEnrolment
{
    /**
     * @var integer|null
     * @ORM\Id
     * @ORM\Column(type="integer", name="gibbonStudentEnrolmentID", columnDefinition="INT(8) UNSIGNED ZEROFILL AUTO_INCREMENT")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var Person|null
     * @ORM\ManyToOne(targetEntity="Kookaburra\UserAdmin\Entity\Person", inversedBy="studentEnrolments")
     * @ORM\JoinColumn(name="gibbonPersonID",referencedColumnName="id", nullable=false)
     */
    private $person;

    /**
     * @var AcademicYear|null
     * @ORM\ManyToOne(targetEntity="Kookaburra\SchoolAdmin\Entity\AcademicYear")
     * @ORM\JoinColumn(name="AcademicYearID", referencedColumnName="id", nullable=false)
     *
     */
    private $academicYear;

    /**
     * @var YearGroup|null
     * @ORM\ManyToOne(targetEntity="Kookaburra\SchoolAdmin\Entity\YearGroup")
     * @ORM\JoinColumn(name="gibbonYearGroupID",referencedColumnName="id", nullable=false)
     */
    private $yearGroup;

    /**
     * @var RollGroup|null
     * @ORM\ManyToOne(targetEntity="Kookaburra\RollGroups\Entity\RollGroup", inversedBy="studentEnrolments")
     * @ORM\JoinColumn(name="gibbonRollGroupID",referencedColumnName="id",nullable=false)
     */
    private $rollGroup;

    /**
     * @var integer|null
     * @ORM\Column(type="smallint", nullable=true, name="rollOrder", columnDefinition="INT(2)")
     */
    private $rollOrder;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return StudentEnrolment
     */
    public function setId(?int $id): StudentEnrolment
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return Person|null
     */
    public function getPerson(): ?Person
    {
        return $this->person;
    }

    /**
     * @param Person|null $person
     * @return StudentEnrolment
     */
    public function setPerson(?Person $person): StudentEnrolment
    {
        $this->person = $person;
        return $this;
    }

    /**
     * @return AcademicYear|null
     */
    public function getAcademicYear(): ?AcademicYear
    {
        return $this->academicYear;
    }

    /**
     * @param AcademicYear|null $academicYear
     * @return StudentEnrolment
     */
    public function setAcademicYear(?AcademicYear $academicYear): StudentEnrolment
    {
        $this->academicYear = $academicYear;
        return $this;
    }

    /**
     * @return YearGroup|null
     */
    public function getYearGroup(): ?YearGroup
    {
        return $this->yearGroup;
    }

    /**
     * @param YearGroup|null $yearGroup
     * @return StudentEnrolment
     */
    public function setYearGroup(?YearGroup $yearGroup): StudentEnrolment
    {
        $this->yearGroup = $yearGroup;
        return $this;
    }

    /**
     * @return RollGroup|null
     */
    public function getRollGroup(): ?RollGroup
    {
        return $this->rollGroup;
    }

    /**
     * @param RollGroup|null $rollGroup
     * @return StudentEnrolment
     */
    public function setRollGroup(?RollGroup $rollGroup): StudentEnrolment
    {
        $this->rollGroup = $rollGroup;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getRollOrder(): ?int
    {
        return $this->rollOrder;
    }

    /**
     * @param int|null $rollOrder
     * @return StudentEnrolment
     */
    public function setRollOrder(?int $rollOrder): StudentEnrolment
    {
        $this->rollOrder = $rollOrder;
        return $this;
    }
}