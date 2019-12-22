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
use Kookaburra\SchoolAdmin\Entity\AcademicYear;
use Kookaburra\UserAdmin\Entity\Person;
use Kookaburra\UserAdmin\Entity\StaffAbsenceType;

/**
 * Class StaffAbsence
 * @package App\Entity
 * @ORM\Entity(repositoryClass="App\Repository\StaffAbsenceRepository")
 * @ORM\Table(options={"auto_increment": 1}, name="StaffAbsence")
 */
class StaffAbsence implements EntityInterface
{
    use BooleanList;

    /**
     * @var integer|null
     * @ORM\Id
     * @ORM\Column(type="bigint", name="gibbonStaffAbsenceID", columnDefinition="INT(14) UNSIGNED ZEROFILL AUTO_INCREMENT")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var StaffAbsenceType|null
     * @ORM\ManyToOne(targetEntity="Kookaburra\UserAdmin\Entity\StaffAbsenceType")
     * @ORM\JoinColumn(name="gibbonStaffAbsenceTypeID", referencedColumnName="gibbonStaffAbsenceTypeID",nullable=false)
     */
    private $staffAbsenceType;

    /**
     * @var AcademicYear|null
     * @ORM\ManyToOne(targetEntity="Kookaburra\SchoolAdmin\Entity\AcademicYear")
     * @ORM\JoinColumn(name="gibbonAcademicYearID", referencedColumnName="id",nullable=false)
     */
    private $AcademicYear;

    /**
     * @var Person|null
     * @ORM\OneToOne(targetEntity="Kookaburra\UserAdmin\Entity\Person")
     * @ORM\JoinColumn(name="gibbonPersonID", referencedColumnName="gibbonPersonID",nullable=false)
     */
    private $person;

    /**
     * @var string|null
     * @ORM\Column(length=60, nullable=true)
     */
    private $reason;

    /**
     * @var string|null
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;

    /**
     * @var string|null
     * @ORM\Column(type="text", nullable=true, name="commentConfidential")
     */
    private $commentConfidential;

    /**
     * @var string|null
     * @ORM\Column(length=16, options={"default": "Approved"}, nullable=true)
     */
    private $status = 'Approved';

    /**
     * @var array
     */
    private static $statusList = ['Pending Approval', 'Approved', 'Declined'];

    /**
     * @var string|null
     * @ORM\Column(length=1, options={"default": "N"}, name="coverageRequired")
     */
    private $coverageRequired = 'N';

    /**
     * @var Person|null
     * @ORM\OneToOne(targetEntity="Kookaburra\UserAdmin\Entity\Person")
     * @ORM\JoinColumn(name="gibbonPersonIDApproval", referencedColumnName="gibbonPersonID", nullable=true)
     */
    private $approvedBy;

    /**
     * @var \DateTime|null
     * @ORM\Column(name="timestampApproval", nullable=true, type="datetime")
     */
    private $approvedWhen;

    /**
     * @var string|null
     * @ORM\Column(name="notesApproval", nullable=true, type="text")
     */
    private $approvedNotes;

    /**
     * @var Person|null
     * @ORM\OneToOne(targetEntity="Kookaburra\UserAdmin\Entity\Person")
     * @ORM\JoinColumn(name="gibbonPersonIDCreator", referencedColumnName="gibbonPersonID", nullable=true)
     */
    private $createdBy;

    /**
     * @var \DateTime|null
     * @ORM\Column(name="timestampCreator", type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     */
    private $createdWhen;

    /**
     * @var string|null
     * @ORM\Column(length=1, options={"default": "N"}, name="notificationSent")
     */
    private $notificationSent = 'N';

    /**
     * @var string|null
     * @ORM\Column(type="text", nullable=true, name="notificationList")
     */
    private $notificationList;

    /**
     * @var Group|null
     * @ORM\OneToOne(targetEntity="Group")
     * @ORM\JoinColumn(name="gibbonGroupID", referencedColumnName="gibbonGroupID", nullable=true)
     */
    private $group;

    /**
     * @var string|null
     * @ORM\Column(name="gibbonCalendarEventID", nullable=true, type="text")
     */
    private $calendarEvent;

    /**
     * @var StaffAbsenceDate
     * @ORM\OneToOne(targetEntity="App\Entity\StaffAbsenceDate", mappedBy="staffAbsence")
     */
    private $staffAbsenceDate;

    /**
     * StaffAbsence constructor.
     */
    public function __construct()
    {
        $this->createdWhen = new \DateTime();
    }

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
     * @return StaffAbsence
     */
    public function setId(?int $id): StaffAbsence
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return StaffAbsenceType|null
     */
    public function getStaffAbsenceType(): ?StaffAbsenceType
    {
        return $this->staffAbsenceType;
    }

    /**
     * StaffAbsenceType.
     *
     * @param StaffAbsenceType|null $staffAbsenceType
     * @return StaffAbsence
     */
    public function setStaffAbsenceType(?StaffAbsenceType $staffAbsenceType): StaffAbsence
    {
        $this->staffAbsenceType = $staffAbsenceType;
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
     * AcademicYear.
     *
     * @param AcademicYear|null $AcademicYear
     * @return StaffAbsence
     */
    public function setAcademicYear(?AcademicYear $AcademicYear): StaffAbsence
    {
        $this->AcademicYear = $AcademicYear;
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
     * Person.
     *
     * @param Person|null $person
     * @return StaffAbsence
     */
    public function setPerson(?Person $person): StaffAbsence
    {
        $this->person = $person;
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
     * @return StaffAbsence
     */
    public function setReason(?string $reason): StaffAbsence
    {
        $this->reason = $reason;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getComment(): ?string
    {
        return $this->comment;
    }

    /**
     * Comment.
     *
     * @param string|null $comment
     * @return StaffAbsence
     */
    public function setComment(?string $comment): StaffAbsence
    {
        $this->comment = $comment;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCommentConfidential(): ?string
    {
        return $this->commentConfidential;
    }

    /**
     * CommentConfidential.
     *
     * @param string|null $commentConfidential
     * @return StaffAbsence
     */
    public function setCommentConfidential(?string $commentConfidential): StaffAbsence
    {
        $this->commentConfidential = $commentConfidential;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getStatus(): ?string
    {
        return in_array($this->status, self::getStatusList()) ? $this->status : null;
    }

    /**
     * Status.
     *
     * @param string|null $status
     * @return StaffAbsence
     */
    public function setStatus(?string $status): StaffAbsence
    {
        $this->status = in_array($status, self::getStatusList()) ? $status : null ;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCoverageRequired(): ?string
    {
        return self::checkBoolean($this->coverageRequired, 'N');
    }

    /**
     * CoverageRequired.
     *
     * @param string|null $coverageRequired
     * @return StaffAbsence
     */
    public function setCoverageRequired(?string $coverageRequired): StaffAbsence
    {
        $this->coverageRequired = self::checkBoolean($coverageRequired, 'N');
        return $this;
    }

    /**
     * @return Person|null
     */
    public function getApprovedBy(): ?Person
    {
        return $this->approvedBy;
    }

    /**
     * ApprovedBy.
     *
     * @param Person|null $approvedBy
     * @return StaffAbsence
     */
    public function setApprovedBy(?Person $approvedBy): StaffAbsence
    {
        $this->approvedBy = $approvedBy;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getApprovedWhen(): ?\DateTime
    {
        return $this->approvedWhen;
    }

    /**
     * ApprovedWhen.
     *
     * @param \DateTime|null $approvedWhen
     * @return StaffAbsence
     */
    public function setApprovedWhen(?\DateTime $approvedWhen): StaffAbsence
    {
        $this->approvedWhen = $approvedWhen;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getApprovedNotes(): ?string
    {
        return $this->approvedNotes;
    }

    /**
     * ApprovedNotes.
     *
     * @param string|null $approvedNotes
     * @return StaffAbsence
     */
    public function setApprovedNotes(?string $approvedNotes): StaffAbsence
    {
        $this->approvedNotes = $approvedNotes;
        return $this;
    }

    /**
     * @return Person|null
     */
    public function getCreatedBy(): ?Person
    {
        return $this->createdBy;
    }

    /**
     * CreatedBy.
     *
     * @param Person|null $createdBy
     * @return StaffAbsence
     */
    public function setCreatedBy(?Person $createdBy): StaffAbsence
    {
        $this->createdBy = $createdBy;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getCreatedWhen(): ?\DateTime
    {
        return $this->createdWhen;
    }

    /**
     * CreatedWhen.
     *
     * @param \DateTime|null $createdWhen
     * @return StaffAbsence
     */
    public function setCreatedWhen(?\DateTime $createdWhen): StaffAbsence
    {
        $this->createdWhen = $createdWhen;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getNotificationSent(): ?string
    {
        return self::checkBoolean($this->notificationSent, 'N');
    }

    /**
     * NotificationSent.
     *
     * @param string|null $notificationSent
     * @return StaffAbsence
     */
    public function setNotificationSent(?string $notificationSent): StaffAbsence
    {
        $this->notificationSent = self::checkBoolean($notificationSent, 'N');
        return $this;
    }

    /**
     * @return string|null
     */
    public function getNotificationList(): ?string
    {
        return $this->notificationList;
    }

    /**
     * NotificationList.
     *
     * @param string|null $notificationList
     * @return StaffAbsence
     */
    public function setNotificationList(?string $notificationList): StaffAbsence
    {
        $this->notificationList = $notificationList;
        return $this;
    }

    /**
     * @return Group|null
     */
    public function getGroup(): ?Group
    {
        return $this->group;
    }

    /**
     * Group.
     *
     * @param Group|null $group
     * @return StaffAbsence
     */
    public function setGroup(?Group $group): StaffAbsence
    {
        $this->group = $group;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCalendarEvent(): ?string
    {
        return $this->calendarEvent;
    }

    /**
     * CalendarEvent.
     *
     * @param string|null $calendarEvent
     * @return StaffAbsence
     */
    public function setCalendarEvent(?string $calendarEvent): StaffAbsence
    {
        $this->calendarEvent = $calendarEvent;
        return $this;
    }

    /**
     * @return array
     */
    public static function getStatusList(): array
    {
        return self::$statusList;
    }

    /**
     * @return StaffAbsenceDate
     */
    public function getStaffAbsenceDate(): StaffAbsenceDate
    {
        return $this->staffAbsenceDate;
    }

    /**
     * StaffAbsenceDate.
     *
     * @param StaffAbsenceDate $staffAbsenceDate
     * @return StaffAbsence
     */
    public function setStaffAbsenceDate(StaffAbsenceDate $staffAbsenceDate): StaffAbsence
    {
        $this->staffAbsenceDate = $staffAbsenceDate;
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