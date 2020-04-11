<?php
/**
 * Created by PhpStorm.
 *
 * Kookaburra
 *
 * (c) 2018 Craig Rayner <craig@craigrayner.com>
 *
 * UserProvider: craig
 * Date: 23/11/2018
 * Time: 15:27
 */
namespace App\Entity;

use App\Manager\EntityInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;
use Gibbon\Domain\System\Module;
use Kookaburra\SchoolAdmin\Entity\AcademicYear;
use Kookaburra\UserAdmin\Entity\Person;

/**
 * Class Log
 * @package App\Entity
 * @ORM\Entity(repositoryClass="App\Repository\LogRepository")
 * @ORM\Table(options={"auto_increment": 1}, name="Log")
 * @ORM\HasLifecycleCallbacks()
 */
class Log implements EntityInterface
{
    /**
     * @var integer|null
     * @ORM\Id
     * @ORM\Column(type="bigint", name="gibbonLogID", columnDefinition="INT(16) UNSIGNED AUTO_INCREMENT")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var Module|null
     * @ORM\ManyToOne(targetEntity="Kookaburra\SystemAdmin\Entity\Module")
     * @ORM\JoinColumn(name="gibbonModuleID",referencedColumnName="id")
     */
    private $module;

    /**
     * @var Person|null
     * @ORM\ManyToOne(targetEntity="Kookaburra\UserAdmin\Entity\Person")
     * @ORM\JoinColumn(name="gibbonPersonID",referencedColumnName="id")
     */
    private $person;

    /**
     * @var AcademicYear|null
     * @ORM\ManyToOne(targetEntity="Kookaburra\SchoolAdmin\Entity\AcademicYear")
     * @ORM\JoinColumn(name="gibbonAcademicYearID", referencedColumnName="id", nullable=false)
     *
     */
    private $AcademicYear;

    /**
     * @var \DateTime|null
     * @ORM\Column(type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     */
    private $timestamp;

    /**
     * @var string|null
     * @ORM\Column(length=50)
     */
    private $title;

    /**
     * @var array|null
     * @ORM\Column(type="simple_array", nullable=true, name="serialisedArray")
     */
    private $serialisedArray;

    /**
     * @var string|null
     * @ORM\Column(length=15, nullable=true)
     */
    private $ip;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return Log
     */
    public function setId(?int $id): Log
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return Module|null
     */
    public function getModule(): ?Module
    {
        return $this->module;
    }

    /**
     * @param Module|null $module
     * @return Log
     */
    public function setModule(?Module $module): Log
    {
        $this->module = $module;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getPerson(): ?int
    {
        return $this->person;
    }

    /**
     * setPerson
     * @param Person|null $person
     * @return Log
     */
    public function setPerson(?Person $person): Log
    {
        $this->person = $person;
        return $this;
    }

    /**
     * @return AcademicYear|null
     */
    public function getAcademicYear(): ?AcademicYear
    {
        return $this->AcademicYear;
    }

    /**
     * @param AcademicYear|null $AcademicYear
     * @return Log
     */
    public function setAcademicYear(?AcademicYear $AcademicYear): Log
    {
        $this->AcademicYear = $AcademicYear;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getTimestamp(): ?\DateTime
    {
        return $this->timestamp;
    }

    /**
     * @param \DateTime|null $timestamp
     * @return Log
     */
    public function setTimestamp(?\DateTime $timestamp): Log
    {
        $this->timestamp = $timestamp;
        return $this;
    }

    /**
     * @param LifecycleEventArgs $event
     * @ORM\PrePersist()
     * @return Log
     */
    public function createTimestamp(LifecycleEventArgs $event): Log
    {
        return $this->setTimestamp(new \DateTime());
    }


    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     * @return Log
     */
    public function setTitle(?string $title): Log
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return array|null
     */
    public function getSerialisedArray(): ?array
    {
        return $this->serialisedArray;
    }

    /**
     * setSerialisedArray
     * @param array|null $serialisedArray
     * @return Log
     */
    public function setSerialisedArray(?array $serialisedArray): Log
    {
        $this->serialisedArray = $serialisedArray;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getIp(): ?string
    {
        return $this->ip;
    }

    /**
     * @param string|null $ip
     * @return Log
     */
    public function setIp(?string $ip): Log
    {
        $this->ip = $ip;
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