<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 9/12/2019
 * Time: 12:04
 */

namespace App\Entity;

use App\Manager\EntityInterface;
use App\Manager\Traits\BooleanList;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class StudentNoteCategory
 * @package App\Entity
 * @ORM\Entity(repositoryClass="App\Repository\StudentNoteCategoryRepository")
 * @ORM\Table(options={"auto_increment": 1}, name="StudentNoteCategory", uniqueConstraints={@ORM\UniqueConstraint(name="name", columns={"name"})})
 * @UniqueEntity({"name"})
 */
class StudentNoteCategory implements EntityInterface
{
    use BooleanList;

    /**
     * @var integer|null
     * @ORM\Id
     * @ORM\Column(type="int", name="gibbonStudentNoteCategoryID", columnDefinition="INT(5) UNSIGNED ZEROFILL")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var string|null
     * @ORM\Column(length=30,nullable=true)
     * @Assert\NotBlank()
     * @Assert\Length(max=30)
     */
    private $name;

    /**
     * @var string|null
     * @ORM\Column(type="text",nullable=true,unique=true)
     */
    private $template;

    /**
     * @var string
     * @ORM\Column(length=1,options={"default": "Y"})
     * @Assert\Choice(callback="getBooleanList")
     */
    private $active = 'Y';

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
     * @return StudentNoteCategory
     */
    public function setId(?int $id): StudentNoteCategory
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
     * Name.
     *
     * @param string|null $name
     * @return StudentNoteCategory
     */
    public function setName(?string $name): StudentNoteCategory
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getTemplate(): ?string
    {
        return $this->template;
    }

    /**
     * Template.
     *
     * @param string|null $template
     * @return StudentNoteCategory
     */
    public function setTemplate(?string $template): StudentNoteCategory
    {
        $this->template = $template;
        return $this;
    }

    /**
     * @return string
     */
    public function getActive(): string
    {
        return $this->active;
    }

    /**
     * Active.
     *
     * @param string $active
     * @return StudentNoteCategory
     */
    public function setActive(string $active): StudentNoteCategory
    {
        $this->active = $active;
        return $this;
    }

    /**
     * toArray
     * @param string|null $name
     * @return array
     */
    public function toArray(?string $name = null) : array
    {
        return [];
    }
}