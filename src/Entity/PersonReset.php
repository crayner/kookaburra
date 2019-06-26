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

use Doctrine\ORM\Mapping as ORM;

/**
 * Class PersonReset
 * @package App\Entity
 * @ORM\Entity(repositoryClass="App\Repository\PersonResetRepository")
 * @ORM\Table(name="PersonReset")
 * @ORM\HasLifecycleCallbacks()
 */
class PersonReset
{
    /**
     * @var integer|null
     * @ORM\Id
     * @ORM\Column(type="bigint", name="gibbonPersonResetID", columnDefinition="INT(12) UNSIGNED ZEROFILL")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var Person|null
     * @ORM\ManyToOne(targetEntity="Person")
     * @ORM\JoinColumn(name="gibbonPersonID", referencedColumnName="gibbonPersonID", nullable=false)
     */
    private $person;

    /**
     * @var string|null
     * @ORM\Column(length=40)
     */
    private $key;

    /**
     * @var \DateTime|null
     * @ORM\Column(type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     */
    private $timestamp;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return PersonReset
     */
    public function setId(?int $id): PersonReset
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
     * @return PersonReset
     */
    public function setPerson(?Person $person): PersonReset
    {
        $this->person = $person;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getKey(): ?string
    {
        return $this->key;
    }

    /**
     * @param string|null $key
     * @return PersonReset
     */
    public function setKey(?string $key): PersonReset
    {
        $this->key = $key;
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
     * setTimestamp
     * @param \DateTime|null $timestamp
     * @return PersonReset
     * @throws \Exception
     * @ORM\PrePersist()
     */
    public function setTimestamp(?\DateTime $timestamp = null): PersonReset
    {
        $this->timestamp = $timestamp ?: new \DateTime('now');
        return $this;
    }
}