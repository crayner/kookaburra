<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 20/09/2019
 * Time: 13:50
 */

namespace App\Entity;

use App\Manager\EntityInterface;
use App\Util\UserHelper;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class ImportHistory
 * @package App\Entity
 * @ORM\Entity(repositoryClass="App\Repository\ImportHistoryRepository")
 * @ORM\Table(options={"auto_increment": 1}, name="ImportHistory")
 * @ORM\HasLifecycleCallbacks()
 */
class ImportHistory implements EntityInterface
{
    /**
     * @var integer|null
     * @ORM\Id
     * @ORM\Column(type="integer", columnDefinition="AUTO_INCREMENT")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var string|null
     * @ORM\Column(length=32)
     */
    private $importType;

    /**
     * @var \DateTime|null
     * @ORM\Column(type="datetime", name="last_modified")
     */
    private $lastModified;

    /**
     * @var Person|null
     * @ORM\ManyToOne(targetEntity="App\Entity\Person")
     * @ORM\JoinColumn(name="performed_by", referencedColumnName="gibbonPersonID")
     */
    private $performedBy;

    /**
     * @var array
     * @ORM\Column(type="array", name="column_order")
     */
    private $columnOrder = [];

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
     * @return ImportHistory
     */
    public function setId(?int $id): ImportHistory
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getImportType(): ?string
    {
        return $this->importType;
    }

    /**
     * ImportType.
     *
     * @param string|null $importType
     * @return ImportHistory
     */
    public function setImportType(?string $importType): ImportHistory
    {
        $this->importType = $importType;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getLastModified(): ?\DateTime
    {
        return $this->lastModified;
    }

    /**
     * LastModified.
     *
     * @param \DateTime|null $lastModified
     * @ORM\PreUpdate()
     * @ORM\PrePersist()
     * @return ImportHistory
     */
    public function setLastModified(?\DateTime $lastModified): ImportHistory
    {
        if (null === $lastModified && null === $this->getLastModified())
            $lastModified = new \DateTime();
        $this->lastModified = $lastModified;
        return $this;
    }

    /**
     * @return Person|null
     */
    public function getPerformedBy(): ?Person
    {
        return $this->performedBy;
    }

    /**
     * setPerformedBy
     * @param Person|null $performedBy
     * @return ImportHistory
     * @ORM\PreUpdate()
     * @ORM\PrePersist()
     * @throws \Exception
     */
    public function setPerformedBy(?Person $performedBy): ImportHistory
    {
        if (null === $performedBy && null === $this->getPerformedBy())
            $performedBy = UserHelper::getCurrentUser();
        $this->performedBy = $performedBy;
        return $this;
    }

    /**
     * @return array
     */
    public function getColumnOrder(): array
    {
        return $this->columnOrder;
    }

    /**
     * ColumnOrder.
     *
     * @param array $columnOrder
     * @return ImportHistory
     */
    public function setColumnOrder(array $columnOrder): ImportHistory
    {
        $this->columnOrder = $columnOrder;
        return $this;
    }
}