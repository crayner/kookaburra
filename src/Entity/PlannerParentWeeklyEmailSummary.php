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
namespace App\Entity;

use App\Manager\Traits\BooleanList;
use Doctrine\ORM\Mapping as ORM;
use Kookaburra\SchoolAdmin\Entity\AcademicYear;
use Kookaburra\UserAdmin\Entity\Person;

/**
 * Class PlannerParentWeeklyEmailSummary
 * @package App\Entity
 * @ORM\Entity(repositoryClass="App\Repository\PlannerParentWeeklyEmailSummaryRepository")
 * @ORM\Table(options={"auto_increment": 1}, name="PlannerParentWeeklyEmailSummary", uniqueConstraints={@ORM\UniqueConstraint(name="key", columns={"key"})})
 */
class PlannerParentWeeklyEmailSummary
{
    use BooleanList;

    /**
     * @var integer|null
     * @ORM\Id()
     * @ORM\Column(type="bigint", name="gibbonPlannerParentWeeklyEmailSummaryID", columnDefinition="INT(14) UNSIGNED AUTO_INCREMENT")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var AcademicYear|null
     * @ORM\ManyToOne(targetEntity="Kookaburra\SchoolAdmin\Entity\AcademicYear")
     * @ORM\JoinColumn(name="gibbonAcademicYearID", referencedColumnName="id", nullable=false)
     */
    private $AcademicYear;

    /**
     * @var Person|null
     * @ORM\ManyToOne(targetEntity="Kookaburra\UserAdmin\Entity\Person")
     * @ORM\JoinColumn(name="gibbonPersonIDParent", referencedColumnName="id", nullable=false)
     */
    private $parent;

    /**
     * @var Person|null
     * @ORM\ManyToOne(targetEntity="Kookaburra\UserAdmin\Entity\Person")
     * @ORM\JoinColumn(name="gibbonPersonIDStudent",referencedColumnName="id", nullable=false)
     */
    private $student;

    /**
     * @var integer|null
     * @ORM\Column(type="smallint", name="weekOfYear", columnDefinition="INT(2)")
     */
    private $weekOfYear;

    /**
     * @var string|null
     * @ORM\Column(length=40, unique=true)
     */
    private $key;

    /**
     * @var string|null
     * @ORM\Column(length=1, options={"default": "N"})
     */
    private $confirmed = 'N';

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return PlannerParentWeeklyEmailSummary
     */
    public function setId(?int $id): PlannerParentWeeklyEmailSummary
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return AcademicYear|null
     */
    public function getAcademicYear(): ?AcademicYear
    {
        return $this->AcademicYear;
    }

    /**
     * @param AcademicYear|null $AcademicYear
     * @return PlannerParentWeeklyEmailSummary
     */
    public function setAcademicYear(?AcademicYear $AcademicYear): PlannerParentWeeklyEmailSummary
    {
        $this->AcademicYear = $AcademicYear;
        return $this;
    }

    /**
     * @return Person|null
     */
    public function getParent(): ?Person
    {
        return $this->parent;
    }

    /**
     * @param Person|null $parent
     * @return PlannerParentWeeklyEmailSummary
     */
    public function setParent(?Person $parent): PlannerParentWeeklyEmailSummary
    {
        $this->parent = $parent;
        return $this;
    }

    /**
     * @return Person|null
     */
    public function getStudent(): ?Person
    {
        return $this->student;
    }

    /**
     * @param Person|null $student
     * @return PlannerParentWeeklyEmailSummary
     */
    public function setStudent(?Person $student): PlannerParentWeeklyEmailSummary
    {
        $this->student = $student;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getWeekOfYear(): ?int
    {
        return $this->weekOfYear;
    }

    /**
     * @param int|null $weekOfYear
     * @return PlannerParentWeeklyEmailSummary
     */
    public function setWeekOfYear(?int $weekOfYear): PlannerParentWeeklyEmailSummary
    {
        $this->weekOfYear = $weekOfYear;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getKey(): ?string
    {
        return $this->key;
    }

    /**
     * @param string|null $key
     * @return PlannerParentWeeklyEmailSummary
     */
    public function setKey(?string $key): PlannerParentWeeklyEmailSummary
    {
        $this->key = $key;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getConfirmed(): ?string
    {
        return $this->confirmed;
    }

    /**
     * @param string|null $confirmed
     * @return PlannerParentWeeklyEmailSummary
     */
    public function setConfirmed(?string $confirmed): PlannerParentWeeklyEmailSummary
    {
        $this->confirmed = $confirmed;
        return $this;
    }
}