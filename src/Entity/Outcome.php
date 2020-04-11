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
use App\Validator as Correct;
use Doctrine\ORM\Mapping as ORM;
use Kookaburra\Departments\Entity\Department;
use Kookaburra\UserAdmin\Entity\Person;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Outcome
 * @package App\Entity
 * @ORM\Entity(repositoryClass="App\Repository\OutcomeRepository")
 * @ORM\Table(options={"auto_increment": 1}, name="Outcome",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="nameDepartment", columns={"name","department"}),@ORM\UniqueConstraint(name="nameShortDescription", columns={"nameShort","department"})},
 *     indexes={@ORM\Index(name="department",columns={"department"})})
 * @UniqueEntity({"name","department"})
 * @UniqueEntity({"nameShort","department"})
 */
class Outcome
{
    use BooleanList;

    /**
     * @var integer|null
     * @ORM\Id
     * @ORM\Column(type="integer", name="gibbonOutcomeID", columnDefinition="INT(8) UNSIGNED AUTO_INCREMENT")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var string|null
     * @ORM\Column(length=100)
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @var string|null
     * @ORM\Column(length=14, name="nameShort")
     * @Assert\NotBlank()
     */
    private $nameShort;

    /**
     * @var string|null
     * @ORM\Column(length=50)
     * @Assert\NotBlank()
     */
    private $category;

    /**
     * @var string|null
     * @ORM\Column(type="text")
     * @Correct\HTMLTag()
     */
    private $description;

    /**
     * @var string|null
     * @ORM\Column(length=1)
     * @Assert\Choice(callback="getBooleanList")
     */
    private $active = 'N';

    /**
     * @var string|null
     * @ORM\Column(length=16)
     * @Assert\Choice(callback="getScopeList")
     */
    private $scope;

    /**
     * @var array
     */
    private static $scopeList = ['School', 'Learning Area'];

    /**
     * @var Department|null
     * @ORM\ManyToOne(targetEntity="Kookaburra\Departments\Entity\Department")
     * @ORM\JoinColumn(name="department", referencedColumnName="id")
     */
    private $department;

    /**
     * @var array
     * @ORM\Column(type="simple_array", name="gibbonYearGroupIDList")
     * @Correct\YearGroupList()
     */
    private $yearGroupList;

    /**
     * @var Person|null
     * @ORM\ManyToOne(targetEntity="Kookaburra\UserAdmin\Entity\Person")
     * @ORM\JoinColumn(name="gibbonPersonIDCreator", referencedColumnName="id", nullable=false)
     */
    private $personCreator;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return Outcome
     */
    public function setId(?int $id): Outcome
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
     * @param string|null $name
     * @return Outcome
     */
    public function setName(?string $name): Outcome
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
     * @param string|null $nameShort
     * @return Outcome
     */
    public function setNameShort(?string $nameShort): Outcome
    {
        $this->nameShort = $nameShort;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCategory(): ?string
    {
        return $this->category;
    }

    /**
     * @param string|null $category
     * @return Outcome
     */
    public function setCategory(?string $category): Outcome
    {
        $this->category = $category;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     * @return Outcome
     */
    public function setDescription(?string $description): Outcome
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getActive(): ?string
    {
        return $this->active;
    }

    /**
     * @param string|null $active
     * @return Outcome
     */
    public function setActive(?string $active): Outcome
    {
        $this->active = self::checkBoolean($active, 'N');
        return $this;
    }

    /**
     * @return string|null
     */
    public function getScope(): ?string
    {
        return $this->scope;
    }

    /**
     * @param string|null $scope
     * @return Outcome
     */
    public function setScope(?string $scope): Outcome
    {
        $this->scope = in_array($scope, self::getScopeList()) ? $scope : null;
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
     * @return Outcome
     */
    public function setDepartment(?Department $department): Outcome
    {
        $this->department = $department;
        return $this;
    }

    /**
     * @return array
     */
    public function getYearGroupList(): array
    {
        return $this->yearGroupList = $this->yearGroupList ?: [];
    }

    /**
     * YearGroupList.
     *
     * @param array|string $yearGroupList
     * @return Outcome
     */
    public function setYearGroupList($yearGroupList): Outcome
    {
        if (!is_array($yearGroupList))
            $yearGroupList = explode(',', $yearGroupList);

        $this->yearGroupList = $yearGroupList;
        return $this;
    }

    /**
     * @return Person|null
     */
    public function getPersonCreator(): ?Person
    {
        return $this->personCreator;
    }

    /**
     * @param Person|null $personCreator
     * @return Outcome
     */
    public function setPersonCreator(?Person $personCreator): Outcome
    {
        $this->personCreator = $personCreator;
        return $this;
    }

    /**
     * @return array
     */
    public static function getScopeList(): array
    {
        return self::$scopeList;
    }

    /**
     * __toString
     * @return string
     */
    public function __toString(): string
    {
        return $this->getName() . ' -> ' . (null !== $this->getDepartment() ? $this->getDepartment()->__toString() : '');
    }
}