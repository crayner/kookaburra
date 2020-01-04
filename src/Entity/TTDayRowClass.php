<?php
/**
 * Created by PhpStorm.
 *
 * Kookaburra
 *
 * (c) 2018 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 5/12/2018
 * Time: 17:00
 */
namespace App\Entity;

use App\Manager\EntityInterface;
use App\Util\EntityHelper;
use Doctrine\ORM\Mapping as ORM;
use Kookaburra\SchoolAdmin\Entity\Facility;

/**
 * Class TTDayRowClass
 * @package App\Entity
 * @ORM\Entity(repositoryClass="App\Repository\TTDayRowClassRepository")
 * @ORM\Table(options={"auto_increment": 1}, name="TTDayRowClass", indexes={
 *     @ORM\Index(name="gibbonCourseClassID", columns={"gibbonCourseClassID"}),
 *     @ORM\Index(name="facility", columns={"facility"}),
 *     @ORM\Index(name="gibbonTTColumnRowID", columns={"gibbonTTColumnRowID"}),
 *     @ORM\Index(name="gibbonTTDayID", columns={"gibbonTTDayID"})
 * })
 * @ORM\HasLifecycleCallbacks()
 */
class TTDayRowClass implements EntityInterface
{
    /**
     * @var integer|null
     * @ORM\Id
     * @ORM\Column(type="bigint", name="gibbonTTDayRowClassID", columnDefinition="INT(12) UNSIGNED ZEROFILL AUTO_INCREMENT")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var TTColumnRow|null
     * @ORM\ManyToOne(targetEntity="TTColumnRow", inversedBy="TTDayRowClasses")
     * @ORM\JoinColumn(name="gibbonTTColumnRowID", referencedColumnName="gibbonTTColumnRowID", nullable=false)
     */
    private $TTColumnRow;

    /**
     * @var TTDay|null
     * @ORM\ManyToOne(targetEntity="TTDay", inversedBy="TTDayRowClasses")
     * @ORM\JoinColumn(name="gibbonTTDayID", referencedColumnName="gibbonTTDayID", nullable=false)
     */
    private $TTDay;

    /**
     * @var CourseClass|null
     * @ORM\ManyToOne(targetEntity="CourseClass", inversedBy="TTDayRowClasses")
     * @ORM\JoinColumn(name="gibbonCourseClassID", referencedColumnName="gibbonCourseClassID", nullable=false)
     */
    private $courseClass;

    /**
     * @var Facility|null
     * @ORM\ManyToOne(targetEntity="Kookaburra\SchoolAdmin\Entity\Facility")
     * @ORM\JoinColumn(name="facility", referencedColumnName="id")
     */
    private $facility;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return TTDayRowClass
     */
    public function setId(?int $id): TTDayRowClass
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return TTColumnRow|null
     */
    public function getTTColumnRow(): ?TTColumnRow
    {
        return $this->TTColumnRow;
    }

    /**
     * @param TTColumnRow|null $TTColumnRow
     * @return TTDayRowClass
     */
    public function setTTColumnRow(?TTColumnRow $TTColumnRow): TTDayRowClass
    {
        $this->TTColumnRow = $TTColumnRow;
        return $this;
    }

    /**
     * @return TTDay|null
     */
    public function getTTDay(): ?TTDay
    {
        return $this->TTDay;
    }

    /**
     * @param TTDay|null $TTDay
     * @return TTDayRowClass
     */
    public function setTTDay(?TTDay $TTDay): TTDayRowClass
    {
        $this->TTDay = $TTDay;
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
     * @return TTDayRowClass
     */
    public function setCourseClass(?CourseClass $courseClass): TTDayRowClass
    {
        $this->courseClass = $courseClass;
        return $this;
    }

    /**
     * @return Facility|null
     */
    public function getFacility(): ?Facility
    {
        return $this->facility;
    }

    /**
     * Facility.
     *
     * @param Facility|null $facility
     * @return TTDayRowClass
     */
    public function setFacility(?Facility $facility): TTDayRowClass
    {
        $this->facility = $facility;
        return $this;
    }

    /**
     * __toArray
     * @param array $ignore
     * @return array
     */
    public function __toArray(array $ignore = []): array
    {
        return EntityHelper::__toArray(TTDayRowClass::class, $this, $ignore);
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