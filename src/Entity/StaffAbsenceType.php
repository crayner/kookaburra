<?php
/**
 * Created by PhpStorm.
 *
 * bilby
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 24/06/2019
 * Time: 15:30
 */

namespace App\Entity;


use App\Manager\EntityInterface;
use App\Manager\Traits\BooleanList;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class StaffAbsenceType
 * @package App\Entity
 * @ORM\Entity(repositoryClass="App\Repository\StaffAbsenceTypeRepository")
 * @ORM\Table(name="StaffAbsenceType")
 */
class StaffAbsenceType implements EntityInterface
{
    use BooleanList;

    /**
     * @var integer|null
     * @ORM\Id
     * @ORM\Column(type="integer", name="gibbonStaffAbsenceTypeID", columnDefinition="INT(6) UNSIGNED ZEROFILL")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var string|null
     * @ORM\Column(length=60, nullable=true)
     */
    private $name;

    /**
     * @var string|null
     * @ORM\Column(length=10, nullable=true, name="nameShort")
     */
    private $nameShort;

    /**
     * @var string|null
     * @ORM\Column(length=1, options={"default": "Y"}, nullable=true)
     */
    private $active = 'Y';

    /**
     * @var string|null
     * @ORM\Column(length=1, options={"default": "N"}, nullable=true, name="requiresApproval")
     */
    private $requiresApproval = 'N';

    /**
     * @var string|null
     * @ORM\Column(type="text", nullable=true)
     */
    private $reasons;

    /**
     * @var integer|null
     * @ORM\Column(type="smallint", columnDefinition="INT(3)", name="sequenceNumber")
     */
    private $sequenceNumber;

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
     * @return StaffAbsenceType
     */
    public function setId(?int $id): StaffAbsenceType
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Name.
     *
     * @param string|null $name
     * @return StaffAbsenceType
     */
    public function setName(?string $name): StaffAbsenceType
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getNameShort(): ?string
    {
        return $this->nameShort;
    }

    /**
     * NameShort.
     *
     * @param string|null $nameShort
     * @return StaffAbsenceType
     */
    public function setNameShort(?string $nameShort): StaffAbsenceType
    {
        $this->nameShort = $nameShort;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getActive(): ?string
    {
        return self::checkBoolean($this->active);
    }

    /**
     * Active.
     *
     * @param string|null $active
     * @return StaffAbsenceType
     */
    public function setActive(?string $active): StaffAbsenceType
    {
        $this->active = self::checkBoolean($active);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getRequiresApproval(): ?string
    {
        return self::checkBoolean($this->requiresApproval, 'N');
    }

    /**
     * RequiresApproval.
     *
     * @param string|null $requiresApproval
     * @return StaffAbsenceType
     */
    public function setRequiresApproval(?string $requiresApproval): StaffAbsenceType
    {
        $this->requiresApproval = self::checkBoolean($requiresApproval, 'N');
        return $this;
    }

    /**
     * @return string|null
     */
    public function getReasons(): ?string
    {
        return $this->reasons;
    }

    /**
     * Reasons.
     *
     * @param string|null $reasons
     * @return StaffAbsenceType
     */
    public function setReasons(?string $reasons): StaffAbsenceType
    {
        $this->reasons = $reasons;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getSequenceNumber(): ?int
    {
        return $this->sequenceNumber;
    }

    /**
     * SequenceNumber.
     *
     * @param int|null $sequenceNumber
     * @return StaffAbsenceType
     */
    public function setSequenceNumber(?int $sequenceNumber): StaffAbsenceType
    {
        $this->sequenceNumber = $sequenceNumber;
        return $this;
    }
}