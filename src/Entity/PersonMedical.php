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
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class PersonMedical
 * @package App\Entity
 * @ORM\Entity(repositoryClass="App\Repository\PersonMedicalRepository")
 * @ORM\Table(options={"auto_increment": 1}, name="PersonMedical", uniqueConstraints={@ORM\UniqueConstraint(name="student", columns={"gibbonPersonID"})})
 */
class PersonMedical
{
    use BooleanList;

    /**
     * @var integer|null
     * @ORM\Id
     * @ORM\Column(type="integer", name="gibbonPersonMedicalID", columnDefinition="INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var Person|null
     * @ORM\ManyToOne(targetEntity="Person")
     * @ORM\JoinColumn(name="gibbonPersonID",referencedColumnName="gibbonPersonID",nullable=false,unique=true)
     */
    private $person;

    /**
     * @var string
     * @ORM\Column(length=3, name="bloodType")
     * @Assert\Choice(callback="getBloodTypeList")
     */
    private $bloodType = '';

    /**
     * @var array
     */
    private static $bloodTypeList = ['','O+','A+','B+','AB+','O-','A-','B-','AB-'];

    /**
     * @var string|null
     * @ORM\Column(length=1, name="longTermMedication")
     */
    private $longTermMedication = 'N';

    /**
     * @var string|null
     * @ORM\Column(type="text", name="longTermMedicationDetails")
     */
    private $longTermMedicationDetails;

    /**
     * @var string|null
     * @ORM\Column(length=1, name="tetanusWithin10Years")
     */
    private $tetanusWithin10Years;

    /**
     * @var string|null
     * @ORM\Column(type="text")
     */
    private $comment;

    /**
     * @var Collection|PersonMedicalCondition[]
     * @ORM\OneToMany(targetEntity="PersonMedicalCondition", mappedBy="personMedical")
     * @ORM\JoinColumn(name="gibbonPersonMedicalID", referencedColumnName="gibbonPersonMedicalID", nullable=false)
     */
    private $personMedicalConditions;

    /**
     * PersonMedical constructor.
     */
    public function __construct()
    {
        $this->setPersonMedicalConditions(new ArrayCollection());
    }

    /**
     * __toString
     * @return string
     */
    public function __toString(): string
    {
        return $this->getPerson()->formatName();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return PersonMedical
     */
    public function setId(?int $id): PersonMedical
    {
        $this->id = $id;
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
     * @return PersonMedical
     */
    public function setPerson(?Person $person): PersonMedical
    {
        $this->person = $person;
        return $this;
    }

    /**
     * @return string
     */
    public function getBloodType(): string
    {
        return $this->bloodType;
    }

    /**
     * @param string $bloodType
     * @return PersonMedical
     */
    public function setBloodType(string $bloodType): PersonMedical
    {
        $this->bloodType = in_array($bloodType, self::getBloodTypeList()) ? $bloodType : '' ;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getLongTermMedication(): ?string
    {
        return $this->longTermMedication;
    }

    /**
     * @param string|null $longTermMedication
     * @return PersonMedical
     */
    public function setLongTermMedication(?string $longTermMedication): PersonMedical
    {
        $this->longTermMedication = self::checkBoolean($longTermMedication, '');
        return $this;
    }

    /**
     * @return string|null
     */
    public function getLongTermMedicationDetails(): ?string
    {
        return $this->longTermMedicationDetails;
    }

    /**
     * @param string|null $longTermMedicationDetails
     * @return PersonMedical
     */
    public function setLongTermMedicationDetails(?string $longTermMedicationDetails): PersonMedical
    {
        $this->longTermMedicationDetails = $longTermMedicationDetails;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getTetanusWithin10Years(): ?string
    {
        return $this->tetanusWithin10Years;
    }

    /**
     * @param string|null $tetanusWithin10Years
     * @return PersonMedical
     */
    public function setTetanusWithin10Years(?string $tetanusWithin10Years): PersonMedical
    {
        $this->tetanusWithin10Years = self::checkBoolean($tetanusWithin10Years, '');
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
     * @param string|null $comment
     * @return PersonMedical
     */
    public function setComment(?string $comment): PersonMedical
    {
        $this->comment = $comment;
        return $this;
    }

    /**
     * @return array
     */
    public static function getBloodTypeList(): array
    {
        return self::$bloodTypeList;
    }

    /**
     * @return PersonMedicalCondition[]|Collection
     */
    public function getPersonMedicalConditions()
    {
        return $this->personMedicalConditions;
    }

    /**
     * PersonMedicalConditions.
     *
     * @param PersonMedicalCondition[]|Collection $personMedicalConditions
     * @return PersonMedical
     */
    public function setPersonMedicalConditions($personMedicalConditions)
    {
        $this->personMedicalConditions = $personMedicalConditions;
        return $this;
    }
}