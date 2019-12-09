<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 9/12/2019
 * Time: 11:27
 */

namespace App\Entity;

use App\Manager\EntityInterface;
use App\Manager\Traits\BooleanList;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class StaffAbsenceType
 * @package App\Entity
 * @ORM\Entity(repositoryClass="App\Repository\StaffAbsenceTypeRepository")
 * @ORM\Table(options={"auto_increment": 1}, name="StaffAbsence", uniqueConstraints={@ORM\UniqueConstraint(name="name",columns={"name"}), @ORM\UniqueConstraint(name="nameShort",columns={"nameShort"})})
 * @UniqueEntity({"name"})
 * @UniqueEntity({"nameShort"})
 */
class StaffAbsenceType implements EntityInterface
{
    use BooleanList;

    /**
     * @var integer|null
     * @ORM\Id
     * @ORM\Column(type="int", name="gibbonStaffAbsenceTypeID", columnDefinition="INT(6) UNSIGNED ZEROFILL")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var string|null
     * @ORM\Column(length=60,nullable=true,unique=true)
     * @Assert\NotBlank()
     * @Assert\Length(max=60)
     */
    private $name;

    /**
     * @var string|null
     * @ORM\Column(length=10,name="nameShort",nullable=true,unique=true)
     * @Assert\NotBlank()
     * @Assert\Length(max=10)
     */
    private $nameShort;

    /**
     * @var string
     * @ORM\Column(length=1,nullable=false,options={"default": "Y"})
     * @Assert\Choice(callback="getBooleanList")
     */
    private $active = 'Y';

    /**
     * @var string
     * @ORM\Column(length=1,name="requiresApproval",nullable=false,options={"default": "N"})
     * @Assert\Choice(callback="getBooleanList")
     */
    private $requiresApproval = 'N';

    /**
     * @var string|null
     * @ORM\Column(type="text")
     */
    private $reasons;

    /**
     * @var integer
     * @ORM\Column(type="smallint",name="sequenceNumber",columnDefinition="INT(3) UNSIGNED",options={"default": "0"})
     */
    private $sequenceNumber = 0;

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
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->getActive() === 'Y';
    }

    /**
     * @return string
     */
    public function getActive(): string
    {
        return $this->active;
    }

    /**
     * Active.
     *
     * @param string $active
     * @return StaffAbsenceType
     */
    public function setActive(string $active): StaffAbsenceType
    {
        $this->active = self::checkBoolean($active) ? 'Y' : 'N';
        return $this;
    }

    /**
     * @return bool
     */
    public function isRequiresApproval(): bool
    {
        return $this->getRequiresApproval() === 'Y';
    }

    /**
     * @return string
     */
    public function getRequiresApproval(): string
    {
        return $this->requiresApproval;
    }

    /**
     * RequiresApproval.
     *
     * @param string $requiresApproval
     * @return StaffAbsenceType
     */
    public function setRequiresApproval(string $requiresApproval): StaffAbsenceType
    {
        $this->requiresApproval = self::checkBoolean($requiresApproval, 'N') ? 'Y' : 'N';
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
     * @return int
     */
    public function getSequenceNumber(): int
    {
        return $this->sequenceNumber;
    }

    /**
     * SequenceNumber.
     *
     * @param int $sequenceNumber
     * @return StaffAbsenceType
     */
    public function setSequenceNumber(int $sequenceNumber): StaffAbsenceType
    {
        $this->sequenceNumber = $sequenceNumber;
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