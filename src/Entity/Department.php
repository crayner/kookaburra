<?php
/**
 * Created by PhpStorm.
 *
 * Gibbon-Responsive
 *
 * (c) 2018 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 23/11/2018
 * Time: 15:27
 */
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;

/**
 * Class Department
 * @package App\Entity
 * @ORM\Entity(repositoryClass="App\Repository\DepartmentRepository")
 * @ORM\Table(options={"auto_increment": 1}, name="Department")
 */
class Department
{
    /**
     * @var integer|null
     * @ORM\Id()
     * @ORM\Column(type="smallint", name="gibbonDepartmentID", columnDefinition="INT(4) UNSIGNED ZEROFILL AUTO_INCREMENT")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(length=16, options={"default": "Learning Area"})
     */
    private $type = "Learning Area";

    /**
     * @var array
     */
    private static $typeList = ['Learning Area', 'Administration'];

    /**
     * @var string
     * @ORM\Column(length=40)
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(length=4, name="nameShort")
     */
    private $nameShort;

    /**
     * @var string|null
     * @ORM\Column(name="subjectListing")
     */
    private $subjectListing;

    /**
     * @var string|null
     * @ORM\Column(type="text", nullable=true)
     */
    private $blurb;

    /**
     * @var string|null
     * @ORM\Column()
     */
    private $logo;

    /**
     * @var DepartmentStaff|null
     * @ORM\OneToMany(targetEntity="DepartmentStaff", mappedBy="department")
     */
    private $staff;

    /**
     * @var Collection|null
     * @ORM\OneToMany(targetEntity="DepartmentResource", mappedBy="department", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $resources;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return Department
     */
    public function setId(?int $id): Department
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return Department
     */
    public function setType(string $type): Department
    {
        $this->type = in_array($type, self::getTypeList()) ? $type : 'Learning Area';
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Department
     */
    public function setName(string $name): Department
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getNameShort(): string
    {
        return $this->nameShort;
    }

    /**
     * @param string $nameShort
     * @return Department
     */
    public function setNameShort(string $nameShort): Department
    {
        $this->nameShort = $nameShort;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSubjectListing(): ?string
    {
        return $this->subjectListing;
    }

    /**
     * @param string|null $subjectListing
     * @return Department
     */
    public function setSubjectListing(?string $subjectListing): Department
    {
        $this->subjectListing = $subjectListing;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getBlurb(): ?string
    {
        return $this->blurb;
    }

    /**
     * @param string|null $blurb
     * @return Department
     */
    public function setBlurb(?string $blurb): Department
    {
        $this->blurb = $blurb;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getLogo(): ?string
    {
        return $this->logo;
    }

    /**
     * @param string|null $logo
     * @return Department
     */
    public function setLogo(?string $logo): Department
    {
        $this->logo = $logo;
        return $this;
    }

    /**
     * @return array
     */
    public static function getTypeList(): array
    {
        return self::$typeList;
    }

    /**
     * getStaff
     * @return Collection|null
     */
    public function getStaff(): ?Collection
    {
        if (null === $this->staff)
            $this->staff = new ArrayCollection();

        if ($this->staff instanceof PersistentCollection)
            $this->staff->initialize();

        return $this->staff;
    }

    /**
     * Staff.
     *
     * @param DepartmentStaff|null $staff
     * @return Department
     */
    public function setStaff(?Collection $staff): Department
    {
        $this->staff = $staff;
        return $this;
    }

    /**
     * getResources
     * @return Collection
     */
    public function getResources(): Collection
    {
        if (null === $this->resources)
            $this->resources = new ArrayCollection();
        if ($this->resources instanceof PersistentCollection)
            $this->resources->initialize();

        return $this->resources;
    }

    /**
     * Resources.
     *
     * @param Collection|null $resources
     * @return Department
     */
    public function setResources(?Collection $resources): Department
    {
        $this->resources = $resources;
        return $this;
    }
}