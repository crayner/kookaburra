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
use Kookaburra\SchoolAdmin\Entity\ScaleGrade;

/**
 * Class RubricColumn
 * @package App\Entity
 * @ORM\Entity(repositoryClass="App\Repository\RubricColumnRepository")
 * @ORM\Table(options={"auto_increment": 1}, name="RubricColumn", indexes={@ORM\Index(name="gibbonRubricID", columns={"gibbonRubricID"})})
 * @ORM\HasLifecycleCallbacks()
 */
class RubricColumn
{
    use BooleanList;

    /**
     * @var integer|null
     * @ORM\Id
     * @ORM\Column(type="integer", name="gibbonRubricColumnID", columnDefinition="INT(9) UNSIGNED AUTO_INCREMENT")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var Rubric|null
     * @ORM\ManyToOne(targetEntity="Rubric")
     * @ORM\JoinColumn(name="gibbonRubricID", referencedColumnName="gibbonRubricID", nullable=false)
     */
    private $rubric;

    /**
     * @var string|null
     * @ORM\Column(length=20)
     */
    private $title;

    /**
     * @var integer
     * @ORM\Column(type="smallint",columnDefinition="INT(2)",name="sequenceNumber")
     */
    private $sequenceNumber;

    /**
     * @var ScaleGrade|null
     * @ORM\ManyToOne(targetEntity="Kookaburra\SchoolAdmin\Entity\ScaleGrade")
     * @ORM\JoinColumn(name="gibbonScaleGradeID", referencedColumnName="id")
     */
    private $scaleGrade;

    /**
     * @var string|null
     * @ORM\Column(length=1, options={"default": "Y"})
     */
    private $visualise = 'Y';

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return RubricColumn
     */
    public function setId(?int $id): RubricColumn
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return Rubric|null
     */
    public function getRubric(): ?Rubric
    {
        return $this->rubric;
    }

    /**
     * @param Rubric|null $rubric
     * @return RubricColumn
     */
    public function setRubric(?Rubric $rubric): RubricColumn
    {
        $this->rubric = $rubric;
        return $this;
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
     * @return RubricColumn
     */
    public function setTitle(?string $title): RubricColumn
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return int
     */
    public function getSequenceNumber(): int
    {
        return $this->sequenceNumber;
    }

    /**
     * @param int $sequenceNumber
     * @return RubricColumn
     */
    public function setSequenceNumber(int $sequenceNumber): RubricColumn
    {
        $this->sequenceNumber = $sequenceNumber;
        return $this;
    }

    /**
     * @return ScaleGrade|null
     */
    public function getScaleGrade(): ?ScaleGrade
    {
        return $this->scaleGrade;
    }

    /**
     * @param ScaleGrade|null $scaleGrade
     * @return RubricColumn
     */
    public function setScaleGrade(?ScaleGrade $scaleGrade): RubricColumn
    {
        $this->scaleGrade = $scaleGrade;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getVisualise(): ?string
    {
        return $this->visualise;
    }

    /**
     * @param string|null $visualise
     * @return RubricColumn
     */
    public function setVisualise(?string $visualise): RubricColumn
    {
        $this->visualise = self::checkBoolean($visualise);
        return $this;
    }
}