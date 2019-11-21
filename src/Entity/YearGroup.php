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

use App\Manager\EntityInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class YearGroup
 * @package App\Entity
 * @ORM\Entity(repositoryClass="App\Repository\YearGroupRepository")
 * @ORM\Table(options={"auto_increment": 1}, name="YearGroup", uniqueConstraints={@ORM\UniqueConstraint(name="name", columns={"name"}),@ORM\UniqueConstraint(name="nameShort", columns={"nameShort"}),@ORM\UniqueConstraint(name="sequenceNumber", columns={"sequenceNumber"})})
 */
class YearGroup implements EntityInterface
{
    /**
     * @var integer|null
     * @ORM\Id
     * @ORM\Column(type="smallint", name="gibbonYearGroupID", columnDefinition="INT(3) UNSIGNED ZEROFILL AUTO_INCREMENT")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var string|null
     * @ORM\Column(length=15,unique=true)
     * @Assert\NotBlank(message="Your request failed because your inputs were invalid.")
     */
    private $name;

    /**
     * @var string|null
     * @ORM\Column(length=4,name="nameShort",unique=true)
     * @Assert\NotBlank(message="Your request failed because your inputs were invalid.")
     */
    private $nameShort;

    /**
     * @var integer
     * @ORM\Column(type="smallint",columnDefinition="INT(3) UNSIGNED",name="sequenceNumber",unique=true)
     * @Assert\NotBlank(message="Your request failed because your inputs were invalid.")
     * @Assert\Range(min=1,max=999)
    ")
     */
    private $sequenceNumber;

    /**
     * @var Person|null
     * @ORM\ManyToOne(targetEntity="Person")
     * @ORM\JoinColumn(name="gibbonPersonIDHOY",referencedColumnName="gibbonPersonID")
     * @Assert\Valid
     */
    private $headOfYear;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return YearGroup
     */
    public function setId(?int $id): YearGroup
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
     * @return YearGroup
     */
    public function setName(?string $name): YearGroup
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
     * @return YearGroup
     */
    public function setNameShort(?string $nameShort): YearGroup
    {
        $this->nameShort = $nameShort;
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
     * @param int $sequenceNumber
     * @return YearGroup
     */
    public function setSequenceNumber(int $sequenceNumber): YearGroup
    {
        $this->sequenceNumber = $sequenceNumber;
        return $this;
    }

    /**
     * @return Person|null
     */
    public function getHeadOfYear(): ?Person
    {
        return $this->headOfYear;
    }

    /**
     * @param Person|null $headOfYear
     * @return YearGroup
     */
    public function setHeadOfYear(?Person $headOfYear): YearGroup
    {
        $this->headOfYear = $headOfYear;
        return $this;
    }

    /**
     * __toString
     * @return string
     */
    public function __toString(): string
    {
        return $this->getName();
    }
}