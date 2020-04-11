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
use App\Manager\Traits\BooleanList;
use Doctrine\ORM\Mapping as ORM;
use Kookaburra\UserAdmin\Entity\Person;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class CourseClassPerson
 * @package App\Entity
 * @ORM\Entity(repositoryClass="App\Repository\CourseClassPersonRepository")
 * @ORM\Table(options={"auto_increment": 1}, name="CourseClassPerson", indexes={@ORM\Index(name="gibbonCourseClassID", columns={"gibbonCourseClassID"}), @ORM\Index(name="gibbonPersonID", columns={"gibbonPersonID", "role"})}, uniqueConstraints={@ORM\UniqueConstraint(name="courseClassPerson",columns={ "gibbonCourseClassID", "gibbonPersonID"})})
 * @UniqueEntity({"courseClass","person"})
 */
class CourseClassPerson implements EntityInterface
{
    use BooleanList;

    /**
     * @var integer|null
     * @ORM\Id()
     * @ORM\Column(type="integer", name="gibbonCourseClassPersonID", columnDefinition="INT(10) UNSIGNED AUTO_INCREMENT")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var CourseClass|null
     * @ORM\ManyToOne(targetEntity="CourseClass", inversedBy="courseClassPeople")
     * @ORM\JoinColumn(name="gibbonCourseClassID", referencedColumnName="gibbonCourseClassID", nullable=false)
     * @Assert\NotBlank()
     */
    private $courseClass;

    /**
     * @var Person|null
     * @ORM\ManyToOne(targetEntity="Kookaburra\UserAdmin\Entity\Person", inversedBy="courseClassPerson")
     * @ORM\JoinColumn(name="gibbonPersonID", referencedColumnName="id", nullable=false)
     * @Assert\NotBlank()
     */
    private $person;

    /**
     * @var string|null
     * @ORM\Column(length=16)
     * @Assert\NotBlank()
     * @Assert\Choice({"Student","Teacher","Assistant","Technician","Parent","Student - Left","Teacher - Left"})
     */
    private $role = '';

    /**
     * @var array
     */
    private static $roleList = ['Student','Teacher','Assistant','Technician','Parent','Student - Left','Teacher - Left'];

    /**
     * @var string|null
     * @ORM\Column(length=1, options={"default": "Y"})
     * @Assert\Choice({"Y","N"})
     */
    private $reportable = 'Y';

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return CourseClassPerson
     */
    public function setId(?int $id): CourseClassPerson
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return CourseClass|null
     */
    public function getCourseClass(): ?CourseClass
    {
        return $this->courseClass;
    }

    /**
     * @param CourseClass|null $courseClass
     * @return CourseClassPerson
     */
    public function setCourseClass(?CourseClass $courseClass): CourseClassPerson
    {
        $this->courseClass = $courseClass;
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
     * @return CourseClassPerson
     */
    public function setPerson(?Person $person): CourseClassPerson
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
     * @return CourseClassPerson
     */
    public function setRole(?string $role): CourseClassPerson
    {
        $this->role = in_array($role, self::getRoleList()) ? $role : '';
        return $this;
    }

    /**
     * @return string|null
     */
    public function getReportable(): ?string
    {
        return $this->reportable;
    }

    /**
     * @param string|null $reportable
     * @return CourseClassPerson
     */
    public function setReportable(?string $reportable): CourseClassPerson
    {
        $this->reportable = self::checkBoolean($reportable, 'Y');
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
        return $this->getCourseClass()->courseClassName(true) . ': ' . $this->getPerson()->formatName();
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