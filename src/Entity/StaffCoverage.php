<?php
/**
 * Created by PhpStorm.
 *
 * bilby
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 24/06/2019
 * Time: 17:54
 */

namespace App\Entity;

use App\Manager\EntityInterface;
use App\Manager\Traits\BooleanList;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class StaffCoverage
 * @package App\Entity
 * @ORM\Entity(repositoryClass="App\Repository\StaffCoverageRepository")
 * @ORM\Table(options={"auto_increment": 1}, name="StaffCoverage")
 */
class StaffCoverage implements EntityInterface
{
    use BooleanList;

    /**
     * @var integer|null
     * @ORM\Id
     * @ORM\Column(type="bigint", name="gibbonStaffCoverageID", columnDefinition="INT(14) UNSIGNED ZEROFILL AUTO_INCREMENT")
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
     * @var SchoolYear|null
     * @ORM\OneToOne(targetEntity="SchoolYear")
     * @ORM\JoinColumn(name="gibbonSchoolYearID", referencedColumnName="gibbonSchoolYearID", nullable=false)
     */
    private $schoolYear;

    /**
     * @var Person|null
     * @ORM\OneToOne(targetEntity="Kookaburra\UserAdmin\Entity\Person")
     * @ORM\JoinColumn(name="gibbonPersonID", referencedColumnName="gibbonPersonID", nullable=false)
     */
    private $person;

    /**
     * @var string|null
     * @ORM\Column(length=12, options={"default": "Requested"})
     */
    private $status = 'Requested';

    private static $statusList = ['Requested','Accepted','Declined','Cancelled'];

    /**
     * @var string|null
     * @ORM\Column(length=12, options={"default": "Broadcast"}, name="requestType")
     */
    private $requestType = 'Broadcast';

    private static $requestTypeList = ['Individual', 'Broadcast', 'Assigned'];

    /**
     * @var string|null
     * @ORM\Column(length=255, name="substituteTypes", nullable=true)
     */
    private $substituteTypes;

    /**
     * @var Person|null
     * @ORM\OneToOne(targetEntity="Kookaburra\UserAdmin\Entity\Person")
     * @ORM\JoinColumn(name="gibbonPersonIDStatus", referencedColumnName="gibbonPersonID", nullable=false)
     */
    private $statusChangedBy;

    /**
     * @var \DateTime|null
     * @ORM\Column(type="datetime", name="timestampStatus", options={"default": "CURRENT_TIMESTAMP"})
     */
    private $statusChangedDate;

    /**
     * @var string|null
     * @ORM\Column(type="text", name="notesStatus", nullable=true)
     */
    private $statusNotes;

    /**
     * @var Person|null
     * @ORM\OneToOne(targetEntity="Kookaburra\UserAdmin\Entity\Person")
     * @ORM\JoinColumn(name="gibbonPersonIDCoverage", referencedColumnName="gibbonPersonID", nullable=true)
     */
    private $coverageChangedBy;

    /**
     * @var \DateTime|null
     * @ORM\Column(type="datetime", name="timestampCoverage", nullable=true)
     */
    private $coverageChangedDate;

    /**
     * @var string|null
     * @ORM\Column(type="text", name="notesCoverage", nullable=true)
     */
    private $coverageNotes;

    /**
     * @var string|null
     * @ORM\Column(length=4, name="attachmentType", nullable=true)
     */
    private $attachmentType;

    private static $attachmentTypeList = ['File', 'HTML', 'Link'];

    /**
     * @var string|null
     * @ORM\Column(type="text", name="attachmentContent", nullable=true)
     */
    private $attachmentContent;

    /**
     * @var string|null
     * @ORM\Column(length=1, name="notificationSent", options={"default": "N"})
     */
    private $notificationSent = 'N';

    /**
     * @var string|null
     * @ORM\Column(type="text", name="notificationList", nullable=true)
     */
    private $notificationList;

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
     * @return StaffCoverage
     */
    public function setId(?int $id): StaffCoverage
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
     * @return StaffCoverage
     */
    public function setStaffAbsence(?StaffAbsence $staffAbsence): StaffCoverage
    {
        $this->staffAbsence = $staffAbsence;
        return $this;
    }

    /**
     * @return SchoolYear|null
     */
    public function getSchoolYear(): ?SchoolYear
    {
        return $this->schoolYear;
    }

    /**
     * SchoolYear.
     *
     * @param SchoolYear|null $schoolYear
     * @return StaffCoverage
     */
    public function setSchoolYear(?SchoolYear $schoolYear): StaffCoverage
    {
        $this->schoolYear = $schoolYear;
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
     * @return StaffCoverage
     */
    public function setPerson(?Person $person): StaffCoverage
    {
        $this->person = $person;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getStatus(): ?string
    {
        return in_array($this->status, self::getStatusList()) ? $this->status : 'Requested';
    }

    /**
     * Status.
     *
     * @param string|null $status
     * @return StaffCoverage
     */
    public function setStatus(?string $status): StaffCoverage
    {
        $this->status = in_array($status, self::getStatusList()) ? $status : 'Requested';
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
     * @return string|null
     */
    public function getRequestType(): ?string
    {
        return in_array($this->requestType, self::getRequestTypeList()) ? $this->requestType : 'Broadcast';
    }

    /**
     * RequestType.
     *
     * @param string|null $requestType
     * @return StaffCoverage
     */
    public function setRequestType(?string $requestType): StaffCoverage
    {
        $this->requestType = in_array($requestType, self::getRequestTypeList()) ? $requestType : 'Broadcast';
        return $this;
    }

    /**
     * @return array
     */
    public static function getRequestTypeList(): array
    {
        return self::$requestTypeList;
    }

    /**
     * @return string|null
     */
    public function getSubstituteTypes(): ?string
    {
        return $this->substituteTypes;
    }

    /**
     * SubstituteTypes.
     *
     * @param string|null $substituteTypes
     * @return StaffCoverage
     */
    public function setSubstituteTypes(?string $substituteTypes): StaffCoverage
    {
        $this->substituteTypes = $substituteTypes;
        return $this;
    }

    /**
     * @return Person|null
     */
    public function getStatusChangedBy(): ?Person
    {
        return $this->statusChangedBy;
    }

    /**
     * StatusChangedBy.
     *
     * @param Person|null $statusChangedBy
     * @return StaffCoverage
     */
    public function setStatusChangedBy(?Person $statusChangedBy): StaffCoverage
    {
        $this->statusChangedBy = $statusChangedBy;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getStatusChangedDate(): ?\DateTime
    {
        return $this->statusChangedDate;
    }

    /**
     * StatusChangedDate.
     *
     * @param \DateTime|null $statusChangedDate
     * @return StaffCoverage
     */
    public function setStatusChangedDate(?\DateTime $statusChangedDate): StaffCoverage
    {
        $this->statusChangedDate = $statusChangedDate;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getStatusNotes(): ?string
    {
        return $this->statusNotes;
    }

    /**
     * StatusNotes.
     *
     * @param string|null $statusNotes
     * @return StaffCoverage
     */
    public function setStatusNotes(?string $statusNotes): StaffCoverage
    {
        $this->statusNotes = $statusNotes;
        return $this;
    }

    /**
     * @return Person|null
     */
    public function getCoverageChangedBy(): ?Person
    {
        return $this->coverageChangedBy;
    }

    /**
     * CoverageChangedBy.
     *
     * @param Person|null $coverageChangedBy
     * @return StaffCoverage
     */
    public function setCoverageChangedBy(?Person $coverageChangedBy): StaffCoverage
    {
        $this->coverageChangedBy = $coverageChangedBy;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getCoverageChangedDate(): ?\DateTime
    {
        return $this->coverageChangedDate;
    }

    /**
     * CoverageChangedDate.
     *
     * @param \DateTime|null $coverageChangedDate
     * @return StaffCoverage
     */
    public function setCoverageChangedDate(?\DateTime $coverageChangedDate): StaffCoverage
    {
        $this->coverageChangedDate = $coverageChangedDate;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCoverageNotes(): ?string
    {
        return $this->coverageNotes;
    }

    /**
     * CoverageNotes.
     *
     * @param string|null $coverageNotes
     * @return StaffCoverage
     */
    public function setCoverageNotes(?string $coverageNotes): StaffCoverage
    {
        $this->coverageNotes = $coverageNotes;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getAttachmentType(): ?string
    {
        return $this->attachmentType;
    }

    /**
     * AttachmentType.
     *
     * @param string|null $attachmentType
     * @return StaffCoverage
     */
    public function setAttachmentType(?string $attachmentType): StaffCoverage
    {
        $this->attachmentType = in_array($attachmentType, self::getAttachmentTypeList()) ? $attachmentType : null;
        return $this;
    }

    /**
     * @return array
     */
    public static function getAttachmentTypeList(): array
    {
        return self::$attachmentTypeList;
    }

    /**
     * @return string|null
     */
    public function getAttachmentContent(): ?string
    {
        return $this->attachmentContent;
    }

    /**
     * AttachmentContent.
     *
     * @param string|null $attachmentContent
     * @return StaffCoverage
     */
    public function setAttachmentContent(?string $attachmentContent): StaffCoverage
    {
        $this->attachmentContent = $attachmentContent;
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
     * @return StaffCoverage
     */
    public function setNotificationSent(?string $notificationSent): StaffCoverage
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
     * @return StaffCoverage
     */
    public function setNotificationList(?string $notificationList): StaffCoverage
    {
        $this->notificationList = $notificationList;
        return $this;
    }
}