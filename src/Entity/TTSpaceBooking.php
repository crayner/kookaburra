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
 * Time: 17:18
 */
namespace App\Entity;

use App\Manager\EntityInterface;
use Doctrine\ORM\Mapping as ORM;
use Kookaburra\UserAdmin\Entity\Person;

/**
 * Class TTSpaceBooking
 * @package App\Entity
 * @ORM\Entity(repositoryClass="App\Repository\TTSpaceBookingRepository")
 * @ORM\Table(options={"auto_increment": 1}, name="TTSpaceBooking")
 */
class TTSpaceBooking  implements EntityInterface
{
    /**
     * @var integer|null
     * @ORM\Id
     * @ORM\Column(type="bigint", name="gibbonTTSpaceBookingID", columnDefinition="INT(12) UNSIGNED ZEROFILL AUTO_INCREMENT")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var string|null
     * @ORM\Column(length=30, name="foreignKey", options={"default": "gibbonSpaceID"})
     */
    private $foreignKey = 'gibbonSpaceID';

    /**
     * @var array
     */
    private static $foreignKeyList = ['gibbonSpaceID', 'library_item_id'];

    /**
     * @var integer|null
     * @ORM\Column(type="integer", name="foreignKeyID", columnDefinition="INT(10) UNSIGNED ZEROFILL")
     */
    private $foreignKeyID;

    /**
     * @var Person|null
     * @ORM\ManyToOne(targetEntity="Kookaburra\UserAdmin\Entity\Person")
     * @ORM\JoinColumn(name="gibbonPersonID", referencedColumnName="gibbonPersonID", nullable=false)
     */
    private $person;

    /**
     * @var \DateTime|null
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @var \DateTime|null
     * @ORM\Column(type="time", name="timeStart")
     */
    private $timeStart;

    /**
     * @var \DateTime|null
     * @ORM\Column(type="time", name="timeEnd")
     */
    private $timeEnd;

    /**
     * @var Facility|null
     */
    private $space;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return TTSpaceBooking
     */
    public function setId(?int $id): TTSpaceBooking
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getForeignKey(): ?string
    {
        return $this->foreignKey;
    }

    /**
     * @param string|null $foreignKey
     * @return TTSpaceBooking
     */
    public function setForeignKey(?string $foreignKey): TTSpaceBooking
    {
        $this->foreignKey = in_array($foreignKey, self::getForeignKeyList()) ? $foreignKey : 'gibbonSpaceID';
        return $this;
    }

    /**
     * @return int|null
     */
    public function getForeignKeyID(): ?int
    {
        return $this->foreignKeyID;
    }

    /**
     * @param int|null $foreignKeyID
     * @return TTSpaceBooking
     */
    public function setForeignKeyID(?int $foreignKeyID): TTSpaceBooking
    {
        $this->foreignKeyID = $foreignKeyID;
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
     * @return TTSpaceBooking
     */
    public function setPerson(?Person $person): TTSpaceBooking
    {
        $this->person = $person;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    /**
     * @param \DateTime|null $date
     * @return TTSpaceBooking
     */
    public function setDate(?\DateTime $date): TTSpaceBooking
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getTimeStart(): ?\DateTime
    {
        return $this->timeStart;
    }

    /**
     * @param \DateTime|null $timeStart
     * @return TTSpaceBooking
     */
    public function setTimeStart(?\DateTime $timeStart): TTSpaceBooking
    {
        $this->timeStart = $timeStart;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getTimeEnd(): ?\DateTime
    {
        return $this->timeEnd;
    }

    /**
     * @param \DateTime|null $timeEnd
     * @return TTSpaceBooking
     */
    public function setTimeEnd(?\DateTime $timeEnd): TTSpaceBooking
    {
        $this->timeEnd = $timeEnd;
        return $this;
    }

    /**
     * @return array
     */
    public static function getForeignKeyList(): array
    {
        return self::$foreignKeyList;
    }

    /**
     * @return Facility|null
     */
    public function getSpace(): ?Facility
    {
        return $this->space;
    }

    /**
     * @param Facility|null $space
     * @return TTSpaceBooking
     */
    public function setSpace(?Facility $space): TTSpaceBooking
    {
        $this->space = $space;
        return $this;
    }

    /**
     * getName
     * @return string|null
     */
    public function getName(): ?string
    {
        if ($this->getForeignKey() === 'gibbonSpaceID' && $this->getSpace() instanceof Facility)
            return $this->getSpace()->getName();
        return null;

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