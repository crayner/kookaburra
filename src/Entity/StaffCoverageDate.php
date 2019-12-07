<?php
/**
 * Created by PhpStorm.
 *
 * bilby
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 25/06/2019
 * Time: 08:53
 */
namespace App\Entity;

use App\Manager\EntityInterface;
use App\Manager\Traits\BooleanList;
use Doctrine\ORM\Mapping as ORM;
use Kookaburra\UserAdmin\Entity\Person;

/**
 * Class StaffCoverageDate
 * @package App\Entity
 * @ORM\Entity(repositoryClass="App\Repository\StaffCoverageDateRepository")
 * @ORM\Table(options={"auto_increment": 1}, name="StaffCoverageDate")
 */
class StaffCoverageDate implements EntityInterface
{
    use BooleanList;

    /**
     * @var integer|null
     * @ORM\Id
     * @ORM\Column(type="bigint", name="gibbonStaffCoverageDateID", columnDefinition="INT(14) UNSIGNED ZEROFILL AUTO_INCREMENT")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var StaffCoverage|null
     * @ORM\OneToOne(targetEntity="StaffCoverage")
     * @ORM\JoinColumn(name="gibbonStaffCoverageID", referencedColumnName="gibbonStaffCoverageID", nullable=true)
     */
    private $staffCoverage;

    /**
     * @var StaffAbsenceDate|null
     * @ORM\OneToOne(targetEntity="StaffAbsenceDate")
     * @ORM\JoinColumn(name="gibbonStaffAbsenceDateID", referencedColumnName="gibbonStaffAbsenceDateID", nullable=true)
     */
    private $staffAbsenceDate;

    /**
     * @var \DateTime|null
     * @ORM\Column(type="date", nullable=true)
     */
    private $date;

    /**
     * @var string|null
     * @ORM\Column(length=1, options={"default": "Y"}, name="allDay")
     */
    private $allDay = 'Y';

    /**
     * @var \DateTime|null
     * @ORM\Column(type="time", name="timeStart", nullable=true)
     */
    private $startTime;

    /**
     * @var \DateTime|null
     * @ORM\Column(type="time", name="timeEnd", nullable=true)
     */
    private $endTime;

    /**
     * @var float|null
     * @ORM\Column(type="decimal", precision=2, scale=1, nullable=true)
     */
    private $value;

    /**
     * @var Person|null
     * @ORM\OneToOne(targetEntity="Kookaburra\UserAdmin\Entity\Person")
     * @ORM\JoinColumn(name="gibbonPersonIDUnavailable", referencedColumnName="gibbonPersonID", nullable=false)
     */
    private $personUnavailable;

    /**
     * @var string|null
     * @ORM\Column(length=255, nullable=true)
     */
    private $reason;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return StaffCoverage|null
     */
    public function getStaffCoverage(): ?StaffCoverage
    {
        return $this->staffCoverage;
    }

    /**
     * StaffCoverage.
     *
     * @param StaffCoverage|null $staffCoverage
     * @return StaffCoverageDate
     */
    public function setStaffCoverage(?StaffCoverage $staffCoverage): StaffCoverageDate
    {
        $this->staffCoverage = $staffCoverage;
        return $this;
    }

    /**
     * @return StaffAbsenceDate|null
     */
    public function getStaffAbsenceDate(): ?StaffAbsenceDate
    {
        return $this->staffAbsenceDate;
    }

    /**
     * StaffAbsenceDate.
     *
     * @param StaffAbsenceDate|null $staffAbsenceDate
     * @return StaffCoverageDate
     */
    public function setStaffAbsenceDate(?StaffAbsenceDate $staffAbsenceDate): StaffCoverageDate
    {
        $this->staffAbsenceDate = $staffAbsenceDate;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    /**
     * Date.
     *
     * @param \DateTime|null $date
     * @return StaffCoverageDate
     */
    public function setDate(?\DateTime $date): StaffCoverageDate
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getAllDay(): ?string
    {
        return $this->allDay;
    }

    /**
     * AllDay.
     *
     * @param string|null $allDay
     * @return StaffCoverageDate
     */
    public function setAllDay(?string $allDay): StaffCoverageDate
    {
        $this->allDay = $allDay;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getStartTime(): ?\DateTime
    {
        return $this->startTime;
    }

    /**
     * StartTime.
     *
     * @param \DateTime|null $startTime
     * @return StaffCoverageDate
     */
    public function setStartTime(?\DateTime $startTime): StaffCoverageDate
    {
        $this->startTime = $startTime;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getEndTime(): ?\DateTime
    {
        return $this->endTime;
    }

    /**
     * EndTime.
     *
     * @param \DateTime|null $endTime
     * @return StaffCoverageDate
     */
    public function setEndTime(?\DateTime $endTime): StaffCoverageDate
    {
        $this->endTime = $endTime;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getValue(): ?float
    {
        return $this->value;
    }

    /**
     * Value.
     *
     * @param float|null $value
     * @return StaffCoverageDate
     */
    public function setValue(?float $value): StaffCoverageDate
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return Person|null
     */
    public function getPersonUnavailable(): ?Person
    {
        return $this->personUnavailable;
    }

    /**
     * PersonUnavailable.
     *
     * @param Person|null $personUnavailable
     * @return StaffCoverageDate
     */
    public function setPersonUnavailable(?Person $personUnavailable): StaffCoverageDate
    {
        $this->personUnavailable = $personUnavailable;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getReason(): ?string
    {
        return $this->reason;
    }

    /**
     * Reason.
     *
     * @param string|null $reason
     * @return StaffCoverageDate
     */
    public function setReason(?string $reason): StaffCoverageDate
    {
        $this->reason = $reason;
        return $this;
    }

    /**
     * toArray
     * @param string|null $name
     * @return array
     */
    public function toArray(?string $name = null): array
    {
        return [];
    }
}