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

use Doctrine\ORM\Mapping as ORM;
use Kookaburra\SchoolAdmin\Entity\ScaleGrade;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class MarkbookTarget
 * @package App\Entity
 * @ORM\Entity(repositoryClass="App\Repository\MarkbookTargetRepository")
 * @ORM\Table(options={"auto_increment": 1}, name="MarkbookTarget", uniqueConstraints={@ORM\UniqueConstraint(name="coursePerson", columns={"gibbonCourseClassID", "gibbonPersonIDStudent"})})
 * @UniqueEntity({"courseClass","student"})
 */
class MarkbookTarget
{
    /**
     * @var integer|null
     * @ORM\Id
     * @ORM\Column(type="bigint", name="gibbonMarkbookTargetID", columnDefinition="INT(14) UNSIGNED ZEROFILL AUTO_INCREMENT")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var CourseClass|null
     * @ORM\ManyToOne(targetEntity="CourseClass")
     * @ORM\JoinColumn(name="gibbonCourseClassID", referencedColumnName="gibbonCourseClassID", nullable=false)
     */
    private $courseClass;

    /**
     * @var Person|null
     * @ORM\ManyToOne(targetEntity="Kookaburra\UserAdmin\Entity\Person")
     * @ORM\JoinColumn(name="gibbonPersonIDStudent", referencedColumnName="gibbonPersonID", nullable=false)
     */
    private $student;

    /**
     * @var ScaleGrade|null
     * @ORM\ManyToOne(targetEntity="Kookaburra\SchoolAdmin\Entity\ScaleGrade")
     * @ORM\JoinColumn(name="gibbonScaleGradeID", referencedColumnName="id")
     */
    private $scaleGrade;

    /**
     * @return ScaleGrade|null
     */
    public function getScaleGrade(): ?ScaleGrade
    {
        return $this->scaleGrade;
    }

    /**
     * @param ScaleGrade|null $scaleGrade
     * @return MarkbookTarget
     */
    public function setScaleGrade(?ScaleGrade $scaleGrade): MarkbookTarget
    {
        $this->scaleGrade = $scaleGrade;
        return $this;
    }
}