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
use Doctrine\ORM\Mapping as ORM;
use Kookaburra\UserAdmin\Entity\Person;

/**
 * Class PlannerEntryStudentTracker
 * @package App\Entity
 * @ORM\Entity(repositoryClass="App\Repository\PlannerEntryStudentTrackerRepository")
 * @ORM\Table(options={"auto_increment": 1}, name="PlannerEntryStudentTracker")
 */
class PlannerEntryStudentTracker
{
    use BooleanList;

    /**
     * @var integer|null
     * @ORM\Id()
     * @ORM\Column(type="bigint", name="gibbonPlannerEntryStudentTrackerID", columnDefinition="INT(16) UNSIGNED AUTO_INCREMENT")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var PlannerEntry|null
     * @ORM\ManyToOne(targetEntity="PlannerEntry")
     * @ORM\JoinColumn(name="gibbonPlannerEntryID", referencedColumnName="gibbonPlannerEntryID", nullable=false)
     */
    private $plannerEntry;

    /**
     * @var Person|null
     * @ORM\ManyToOne(targetEntity="Kookaburra\UserAdmin\Entity\Person")
     * @ORM\JoinColumn(name="gibbonPersonID",referencedColumnName="id", nullable=false)
     */
    private $person;

    /**
     * @var \DateTime|null
     * @ORM\Column(type="datetime", name="homeworkComplete")
     */
    private $homeworkComplete = 'N';

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return PlannerEntryStudentTracker
     */
    public function setId(?int $id): PlannerEntryStudentTracker
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return PlannerEntry|null
     */
    public function getPlannerEntry(): ?PlannerEntry
    {
        return $this->plannerEntry;
    }

    /**
     * @param PlannerEntry|null $plannerEntry
     * @return PlannerEntryStudentTracker
     */
    public function setPlannerEntry(?PlannerEntry $plannerEntry): PlannerEntryStudentTracker
    {
        $this->plannerEntry = $plannerEntry;
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
     * @return PlannerEntryStudentTracker
     */
    public function setPerson(?Person $person): PlannerEntryStudentTracker
    {
        $this->person = $person;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getHomeworkComplete(): ?\DateTime
    {
        return $this->homeworkComplete;
    }

    /**
     * @param \DateTime|null $homeworkComplete
     * @return PlannerEntryStudentTracker
     */
    public function setHomeworkComplete(?\DateTime $homeworkComplete): PlannerEntryStudentTracker
    {
        $this->homeworkComplete = self::checkBoolean($homeworkComplete, 'N');
        return $this;
    }
}