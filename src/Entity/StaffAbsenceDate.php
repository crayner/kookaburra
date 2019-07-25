<?php
/**
 * Created by PhpStorm.
 *
 * bilby
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 24/06/2019
 * Time: 15:27
 */

namespace App\Entity;

use App\Manager\EntityInterface;
use App\Manager\Traits\BooleanList;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class StaffAbsenceDate
 * @package App\Entity
 * @ORM\Entity(repositoryClass="App\Repository\StaffAbsenceDateRepository")
 * @ORM\Table(options={"auto_increment": 1}, name="StaffAbsenceDate")
 */
class StaffAbsenceDate implements EntityInterface
{
    use BooleanList;

    /**
     * @var integer|null
     * @ORM\Id
     * @ORM\Column(type="bigint", name="gibbonStaffAbsenceDateID", columnDefinition="INT(14) UNSIGNED ZEROFILL AUTO_INCREMENT")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var StaffAbsence|null
     * @ORM\OneToOne(targetEntity="StaffAbsence")
     * @ORM\JoinColumn(name="gibbonStaffAbsenceID", referencedColumnName="gibbonStaffAbsenceID", nullable=true)
     */
    private $staffAbsence;

    /**
     * @var \DateTime|null
     * @ORM\Column(type="date", nullable=true)
     */
    private $date;

    /**
     * @var string|null
     * @ORM\Column(length=1, options={"default": "Y"}, nullable=true, name="allDay")
     */
    private $allDay = 'Y';

    /**
     * @var \DateTime|null
     * @ORM\Column(type="time", nullable=true, name="timeStart")
     */
    private $start;

    /**
     * @var \DateTime|null
     * @ORM\Column(type="time", nullable=true, name="timeEnd")
     */
    private $end;

    /**
     * @var float|null
     * @ORM\Column(type="decimal", precision=2, scale=1, options={"default": 1.0})
     */
    private $value;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Id.
     *
     * @param int|null $id
     * @return StaffAbsenceDate
     */
    public function setId(?int $id): StaffAbsenceDate
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return StaffAbsence|null
     */
    public function getStaffAbsence(): ?StaffAbsence
    {
        return $this->staffAbsence;
    }

    /**
     * StaffAbsence.
     *
     * @param StaffAbsence|null $staffAbsence
     * @return StaffAbsenceDate
     */
    public function setStaffAbsence(?StaffAbsence $staffAbsence): StaffAbsenceDate
    {
        $this->staffAbsence = $staffAbsence;
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
     * @return StaffAbsenceDate
     */
    public function setDate(?\DateTime $date): StaffAbsenceDate
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getAllDay(): ?string
    {
        return self::checkBoolean($this->allDay);
    }

    /**
     * AllDay.
     *
     * @param string|null $allDay
     * @return StaffAbsenceDate
     */
    public function setAllDay(?string $allDay): StaffAbsenceDate
    {
        $this->allDay = self::checkBoolean($allDay);
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getStart(): ?\DateTime
    {
        return $this->start;
    }

    /**
     * Start.
     *
     * @param \DateTime|null $start
     * @return StaffAbsenceDate
     */
    public function setStart(?\DateTime $start): StaffAbsenceDate
    {
        $this->start = $start;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getEnd(): ?\DateTime
    {
        return $this->end;
    }

    /**
     * End.
     *
     * @param \DateTime|null $end
     * @return StaffAbsenceDate
     */
    public function setEnd(?\DateTime $end): StaffAbsenceDate
    {
        $this->end = $end;
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
     * @return StaffAbsenceDate
     */
    public function setValue(?float $value): StaffAbsenceDate
    {
        $this->value = $value;
        return $this;
    }
}