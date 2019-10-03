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

use App\Manager\EntityInterface;
use App\Manager\Traits\BooleanList;
use App\Manager\Traits\EntityGlobals;
use App\Util\EntityHelper;
use App\Util\Format;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class RollGroup
 * @package App\Entity
 * @ORM\Entity(repositoryClass="App\Repository\RollGroupRepository")
 * @ORM\Table(options={"auto_increment": 1}, name="RollGroup", uniqueConstraints={@ORM\UniqueConstraint(name="nameSchoolYear", columns={"name","gibbonSchoolYearID"}), @ORM\UniqueConstraint(name="nameShortSchoolYear", columns={"nameShort","gibbonSchoolYearID"})})
 * @UniqueEntity({"name","schoolYear"})
 * @UniqueEntity({"nameShort","schoolYear"})
 */
class RollGroup implements EntityInterface
{
    use BooleanList;

    use EntityGlobals;

    /**
     * @var integer|null
     * @ORM\Id()
     * @ORM\Column(type="smallint", name="gibbonRollGroupID", columnDefinition="INT(5) UNSIGNED ZEROFILL AUTO_INCREMENT")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var SchoolYear|null
     * @ORM\ManyToOne(targetEntity="SchoolYear")
     * @ORM\JoinColumn(name="gibbonSchoolYearID", referencedColumnName="gibbonSchoolYearID", nullable=false)
     */
    private $schoolYear;

    /**
     * @var string|null
     * @ORM\Column(length=10)
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @var string|null
     * @ORM\Column(length=5, name="nameShort")
     * @Assert\NotBlank()
     */
    private $nameShort;

    /**
     * @var Person|null
     * @ORM\ManyToOne(targetEntity="Person")
     * @ORM\JoinColumn(name="gibbonPersonIDTutor",referencedColumnName="gibbonPersonID",nullable=true)
     */
    private $tutor;

    /**
     * @var Person|null
     * @ORM\ManyToOne(targetEntity="Person")
     * @ORM\JoinColumn(name="gibbonPersonIDTutor2",referencedColumnName="gibbonPersonID",nullable=true)
     */
    private $tutor2;

    /**
     * @var Person|null
     * @ORM\ManyToOne(targetEntity="Person")
     * @ORM\JoinColumn(name="gibbonPersonIDTutor3",referencedColumnName="gibbonPersonID",nullable=true)
     */
    private $tutor3;

    /**
     * @var Person|null
     * @ORM\ManyToOne(targetEntity="Person")
     * @ORM\JoinColumn(name="gibbonPersonIDEA",referencedColumnName="gibbonPersonID",nullable=true)
     */
    private $assistant;

    /**
     * @var Person|null
     * @ORM\ManyToOne(targetEntity="Person")
     * @ORM\JoinColumn(name="gibbonPersonIDEA2",referencedColumnName="gibbonPersonID",nullable=true)
     */
    private $assistant2;

    /**
     * @var Person|null
     * @ORM\ManyToOne(targetEntity="Person")
     * @ORM\JoinColumn(name="gibbonPersonIDEA3",referencedColumnName="gibbonPersonID",nullable=true)
     */
    private $assistant3;

    /**
     * @var Space|null
     * @ORM\ManyToOne(targetEntity="Space")
     * @ORM\JoinColumn(name="gibbonSpaceID", referencedColumnName="gibbonSpaceID",nullable=true)
     */
    private $space;

    /**
     * @var RollGroup|null
     * @ORM\ManyToOne(targetEntity="RollGroup")
     * @ORM\JoinColumn(name="gibbonRollGroupIDNext", referencedColumnName="gibbonRollGroupID",nullable=true)
     */
    private $nextRollGroup;

    /**
     * @var string|null
     * @ORM\Column(length=1, options={"default": "Y"})
     * @Assert\Choice(callback="getBooleanList")
     */
    private $attendance = 'Y';

    /**
     * @var string|null
     * @ORM\Column()
     * @Assert\Url()
     */
    private $website;

    /**
     * @var Collection|null
     * @ORM\OneToMany(targetEntity="App\Entity\StudentEnrolment", mappedBy="rollGroup")
     */
    private $studentEnrolments;

    /**
     * RollGroup constructor.
     */
    public function __construct()
    {
        $this->studentEnrolments = new ArrayCollection();
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
     * @return RollGroup
     */
    public function setId(?int $id): RollGroup
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return SchoolYear|null
     */
    public function getSchoolYear(): ?SchoolYear
    {
        return $this->schoolYear;
    }

    /**
     * @param SchoolYear|null $schoolYear
     * @return RollGroup
     */
    public function setSchoolYear(?SchoolYear $schoolYear): RollGroup
    {
        $this->schoolYear = $schoolYear;
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
     * @return RollGroup
     */
    public function setName(?string $name): RollGroup
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
     * @return RollGroup
     */
    public function setNameShort(?string $nameShort): RollGroup
    {
        $this->nameShort = $nameShort;
        return $this;
    }

    /**
     * @return Person|null
     */
    public function getTutor(): ?Person
    {
        return $this->tutor;
    }

    /**
     * @param Person|null $tutor
     * @return RollGroup
     */
    public function setTutor(?Person $tutor): RollGroup
    {
        $this->tutor = $tutor;
        return $this;
    }

    /**
     * @return Person|null
     */
    public function getTutor2(): ?Person
    {
        return $this->tutor2;
    }

    /**
     * @param Person|null $tutor2
     * @return RollGroup
     */
    public function setTutor2(?Person $tutor2): RollGroup
    {
        $this->tutor2 = $tutor2;
        return $this;
    }

    /**
     * @return Person|null
     */
    public function getTutor3(): ?Person
    {
        return $this->tutor3;
    }

    /**
     * @param Person|null $tutor3
     * @return RollGroup
     */
    public function setTutor3(?Person $tutor3): RollGroup
    {
        $this->tutor3 = $tutor3;
        return $this;
    }

    /**
     * @return Person|null
     */
    public function getAssistant(): ?Person
    {
        return $this->assistant;
    }

    /**
     * @param Person|null $assistant
     * @return RollGroup
     */
    public function setAssistant(?Person $assistant): RollGroup
    {
        $this->assistant = $assistant;
        return $this;
    }

    /**
     * @return Person|null
     */
    public function getAssistant2(): ?Person
    {
        return $this->assistant2;
    }

    /**
     * @param Person|null $assistant2
     * @return RollGroup
     */
    public function setAssistant2(?Person $assistant2): RollGroup
    {
        $this->assistant2 = $assistant2;
        return $this;
    }

    /**
     * @return Person|null
     */
    public function getAssistant3(): ?Person
    {
        return $this->assistant3;
    }

    /**
     * @param Person|null $assistant3
     * @return RollGroup
     */
    public function setAssistant3(?Person $assistant3): RollGroup
    {
        $this->assistant3 = $assistant3;
        return $this;
    }

    /**
     * @return Space|null
     */
    public function getSpace(): ?Space
    {
        return $this->space;
    }

    /**
     * @param Space|null $space
     * @return RollGroup
     */
    public function setSpace(?Space $space): RollGroup
    {
        $this->space = $space;
        return $this;
    }

    /**
     * @return RollGroup|null
     */
    public function getNextRollGroup(): ?RollGroup
    {
        return $this->nextRollGroup;
    }

    /**
     * @param RollGroup|null $nextRollGroup
     * @return RollGroup
     */
    public function setNextRollGroup(?RollGroup $nextRollGroup): RollGroup
    {
        $this->nextRollGroup = $nextRollGroup;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getAttendance(): ?string
    {
        return $this->attendance;
    }

    /**
     * @param string|null $attendance
     * @return RollGroup
     */
    public function setAttendance(?string $attendance): RollGroup
    {
        $this->attendance = self::checkBoolean($attendance);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getWebsite(): ?string
    {
        return $this->website;
    }

    /**
     * @param string|null $website
     * @return RollGroup
     */
    public function setWebsite(?string $website): RollGroup
    {
        $this->website = $website;
        return $this;
    }

    /**
     * getStudentEnrolments
     * @param string|null $sortBy
     * @return Collection
     */
    public function getStudentEnrolments(?string $sortBy = ''): Collection
    {
        if (empty($this->studentEnrolments))
            $this->studentEnrolments = new ArrayCollection();

        if ($this->studentEnrolments instanceof PersistentCollection)
            $this->studentEnrolments->initialize();

        if ('' !== $sortBy) {
            $iterator = $this->studentEnrolments->getIterator();
            $iterator->uasort(
                function ($a, $b) use ($sortBy) {
                    if (!$a->getPerson() instanceof Person || !$b->getPerson() instanceof Person)
                        return 1;

                    if (strpos($sortBy, 'rollOrder') === 0)
                        return ($a->getRollOrder().$a->getPerson()->getSurname().$a->getPerson()->getPreferredName() < $b->getRollOrder().$b->getPerson()->getSurname().$b->getPerson()->getPreferredName()) ? -1 : 1;

                    if (strpos($sortBy, 'surname') === 0)
                        return ($a->getPerson()->getSurname().$a->getPerson()->getPreferredName() < $b->getPerson()->getSurname().$b->getPerson()->getPreferredName()) ? -1 : 1;

                    if (strpos($sortBy, 'preferredName') === 0)
                        return ($a->getPerson()->getPreferredName().$a->getPerson()->getSurname() < $b->getPerson()->getPreferredName().$b->getPerson()->getSurname()) ? -1 : 1;

                    return 1;
                }
            );

            $this->studentEnrolments = new ArrayCollection(iterator_to_array($iterator, false));
        }


        return $this->studentEnrolments;
    }

    /**
     * @param Collection|null $studentEnrolments
     * @return RollGroup
     */
    public function setStudentEnrolments(?Collection $studentEnrolments): RollGroup
    {
        $this->studentEnrolments = $studentEnrolments;
        return $this;
    }

    /**
     * __toArray
     * @param array $ignore
     * @return array
     */
    public function __toArray(array $ignore = []): array
    {
        return EntityHelper::__toArray(RollGroup::class, $this, $ignore);
    }

    /**
     * getTutors
     * @return array
     */
    public function getTutors(): array
    {
        $tutors = [];
        if ($this->getTutor())
            $tutors[] = $this->getTutor();
        if ($this->getTutor2())
            $tutors[] = $this->getTutor2();
        if ($this->getTutor3())
            $tutors[] = $this->getTutor3();

        return $tutors;
    }

    /**
     * getFormatTutors
     * @return string
     */
    public function getFormatTutors(array $parameters): string
    {
        $result = Format::nameList($this->getTutors(), 'Staff', false, true, null);
        if (count($result) > 1 && $parameters['formatTutors'])
            $result[0] .= ' (' . $parameters['formatTutors'] . ')';
        return implode('<br/>', $result);
    }

    /**
     * getSpaceName
     * @return string
     */
    public function getSpaceName(): string
    {
        return $this->getSpace() ? $this->getSpace()->getName() : '';
    }

    /**
     * getStudentCount
     * @return int
     */
    public function getStudentCount(): int
    {
        return $this->getStudentEnrolments() ? count($this->getStudentEnrolments()) : '';
    }

    /**
     * getAssistants
     * @return array
     */
    public function getAssistants(): array
    {
        $tutors = [];
        if ($this->getAssistant())
            $tutors[] = $this->getAssistant();
        if ($this->getAssistant2())
            $tutors[] = $this->getAssistant2();
        if ($this->getAssistant3())
            $tutors[] = $this->getAssistant3();

        return $tutors;
    }

    /**
     * __toString
     * @return string
     */
    public function __toString(): string
    {
        return $this->getName() . ' (' . $this->getNameShort() . ')';
    }
}