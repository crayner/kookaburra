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

use Doctrine\ORM\Mapping as ORM;
use Kookaburra\UserAdmin\Entity\Person;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class DepartmentStaff
 * @package App\Entity
 * @ORM\Entity(repositoryClass="App\Repository\DepartmentStaffRepository")
 * @ORM\Table(options={"auto_increment": 1}, name="DepartmentStaff", uniqueConstraints={@ORM\UniqueConstraint(name="departmentPerson",columns={"gibbonDepartmentID","gibbonPersonID"})})
 * @UniqueEntity({"department","person"})
 */
class DepartmentStaff
{
    /**
     * @var integer|null
     * @ORM\Id()
     * @ORM\Column(type="smallint", name="gibbonDepartmentStaffID", columnDefinition="INT(6) UNSIGNED ZEROFILL AUTO_INCREMENT")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var Department|null
     * @ORM\ManyToOne(targetEntity="Department", inversedBy="staff")
     * @ORM\JoinColumn(name="gibbonDepartmentID", referencedColumnName="gibbonDepartmentID", nullable=false)
     */
    private $department;

    /**
     * @var Person|null
     * @ORM\ManyToOne(targetEntity="Kookaburra\UserAdmin\Entity\Person")
     * @ORM\JoinColumn(name="gibbonPersonID", referencedColumnName="gibbonPersonID", nullable=false)
     */
    private $person;

    /**
     * @var string|null
     * @ORM\Column(length=24)
     * @Assert\NotBlank()
     * @Assert\Choice({"Coordinator","Assistant Coordinator","Teacher (Curriculum)","Teacher","Director","Manager","Administrator","Other"})
     */
    private $role;

    /**
     * @var array
     */
    private static $roleList = ['Coordinator','Assistant Coordinator','Teacher (Curriculum)','Teacher','Director','Manager','Administrator','Other'];

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return DepartmentStaff
     */
    public function setId(?int $id): DepartmentStaff
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return Department|null
     */
    public function getDepartment(): ?Department
    {
        return $this->department;
    }

    /**
     * @param Department|null $department
     * @return DepartmentStaff
     */
    public function setDepartment(?Department $department): DepartmentStaff
    {
        $this->department = $department;
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
     * @return DepartmentStaff
     */
    public function setPerson(?Person $person): DepartmentStaff
    {
        $this->person = $person;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getRole(): ?string
    {
        return $this->role;
    }

    /**
     * @param string|null $role
     * @return DepartmentStaff
     */
    public function setRole(?string $role): DepartmentStaff
    {
        $this->role = in_array($role, self::getRoleList()) ? $role : '';
        return $this;
    }

    /**
     * @return array
     */
    public static function getRoleList(): array
    {
        return self::$roleList;
    }

    /**
     * __toString
     * @return string
     */
    public function __toString(): string
    {
        return $this->getDepartment()->__toString() . ': ' . $this->getPerson()->formatName();
    }
}