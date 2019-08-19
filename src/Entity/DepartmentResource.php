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
 * Class DepartmentResource
 * @package App\Entity
 * @ORM\Entity(repositoryClass="App\Repository\DepartmentResourceRepository")
 * @ORM\Table(options={"auto_increment": 1}, name="DepartmentResource")
 */
class DepartmentResource
{
    /**
     * @var integer|null
     * @ORM\Id()
     * @ORM\Column(type="integer", name="gibbonDepartmentResourceID", columnDefinition="INT(8) UNSIGNED ZEROFILL AUTO_INCREMENT")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var Department|null
     * @ORM\ManyToOne(targetEntity="Department", inversedBy="resources")
     * @ORM\JoinColumn(name="gibbonDepartmentID", referencedColumnName="gibbonDepartmentID", nullable=false)
     */
    private $department;

    /**
     * @var string
     * @ORM\Column(length=16)
     */
    private $type;

    /**
     * @var array
     */
    private static $typeList = ['Link', 'File'];

    /**
     * @var string|null
     * @ORM\Column(length=100)
     */
    private $name;

    /**
     * @var string|null
     * @ORM\Column()
     */
    private $url;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return DepartmentResource
     */
    public function setId(?int $id): DepartmentResource
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return Department|null
     */
    public function getDepartment(): ?Department
    {
        return $this->department;
    }

    /**
     * @param Department|null $department
     * @return DepartmentResource
     */
    public function setDepartment(?Department $department): DepartmentResource
    {
        $this->department = $department;
        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return DepartmentResource
     */
    public function setType(string $type): DepartmentResource
    {
        $this->type = in_array($type, self::getTypeList()) ? $type : '';
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
     * @return DepartmentResource
     */
    public function setName(?string $name): DepartmentResource
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @param string|null $url
     * @return DepartmentResource
     */
    public function setUrl(?string $url): DepartmentResource
    {
        $this->url = $url;
        $this->setUrlFile($url)->setUrlLink($url);
        return $this;
    }

    /**
     * @return array
     */
    public static function getTypeList(): array
    {
        return self::$typeList;
    }

    /**
     * toArray
     * @return array
     */
    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'type' => $this->getType(),
            'department' => $this->getDepartment() ? $this->getDepartment()->getId(): null,
            'url' => $this->getUrl(),
        ];
    }

    /**
     * @var string|null
     */
    private $urlLink;

    /**
     * getUrlLink
     * @return string|null
     */
    public function getUrlLink(): ?string
    {
        return $this->getUrl();
    }

    /**
     * UrlLink.
     *
     * @param string|null $urlLink
     * @return DepartmentResource
     */
    public function setUrlLink(?string $urlLink): DepartmentResource
    {
        $this->urlLink = $urlLink;
        return $this;
    }

    /**
     * @var string|null
     */
    private $urlFile;

    /**
     * getUrlLink
     * @return string|null
     */
    public function getUrlFile(): ?string
    {
        return $this->getUrl();
    }

    /**
     * UrlFile.
     *
     * @param string|null $urlFile
     * @return DepartmentResource
     */
    public function setUrlFile(?string $urlFile): DepartmentResource
    {
        $this->urlFile = $urlFile;
        return $this;
    }
}