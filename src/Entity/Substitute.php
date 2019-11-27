<?php
/**
 * Created by PhpStorm.
 *
 * bilby
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 24/06/2019
 * Time: 17:43
 */

namespace App\Entity;

use App\Manager\EntityInterface;
use App\Manager\Traits\BooleanList;
use Doctrine\ORM\Mapping as ORM;
use Kookaburra\UserAdmin\Entity\Person;

/**
 * Class Substitute
 * @package App\Entity
 * @ORM\Entity(repositoryClass="App\Repository\SubstituteRepository")
 * @ORM\Table(options={"auto_increment": 1}, name="Substitute", uniqueConstraints={@ORM\UniqueConstraint(name="gibbonPersonID", columns={"gibbonPersonID"})})
 */
class Substitute implements EntityInterface
{
    use BooleanList;

    /**
     * @var integer|null
     * @ORM\Id
     * @ORM\Column(type="integer", name="gibbonSubstituteID", columnDefinition="INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var Person|null
     * @ORM\OneToOne(targetEntity="Kookaburra\UserAdmin\Entity\Person")
     * @ORM\JoinColumn(name="gibbonPersonID", referencedColumnName="gibbonPersonID", nullable=false)
     */
    private $person;

    /**
     * @var string|null
     * @ORM\Column(length=1, options={"default": "Y"}, nullable=true)
     */
    private $active = 'Y';

    /**
     * @var string|null
     * @ORM\Column(length=60, nullable=true)
     */
    private $type;

    /**
     * @var string|null
     * @ORM\Column(length=255, nullable=true)
     */
    private $details;

    /**
     * @var integer|null
     * @ORM\Column(type="smallint", columnDefinition="INT(2)", options={"default": 0})
     */
    private $priority = 0;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Id.
     *
     * @param int|null $id
     * @return Substitute
     */
    public function setId(?int $id): Substitute
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
     * Person.
     *
     * @param Person|null $person
     * @return Substitute
     */
    public function setPerson(?Person $person): Substitute
    {
        $this->person = $person;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getActive(): ?string
    {
        return self::checkBoolean($this->active);
    }

    /**
     * Active.
     *
     * @param string|null $active
     * @return Substitute
     */
    public function setActive(?string $active): Substitute
    {
        $this->active = self::checkBoolean($active);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * Type.
     *
     * @param string|null $type
     * @return Substitute
     */
    public function setType(?string $type): Substitute
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDetails(): ?string
    {
        return $this->details;
    }

    /**
     * Details.
     *
     * @param string|null $details
     * @return Substitute
     */
    public function setDetails(?string $details): Substitute
    {
        $this->details = $details;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getPriority(): ?int
    {
        return $this->priority;
    }

    /**
     * Priority.
     *
     * @param int|null $priority
     * @return Substitute
     */
    public function setPriority(?int $priority): Substitute
    {
        $this->priority = $priority;
        return $this;
    }
}