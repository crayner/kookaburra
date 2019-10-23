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
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gibbon\Tables\Action;
use Kookaburra\SystemAdmin\Entity\Module;

/**
 * Class NotificationEvent
 * @package App\Entity
 * @ORM\Entity(repositoryClass="App\Repository\NotificationEventRepository")
 * @ORM\Table(options={"auto_increment": 1}, name="NotificationEvent", uniqueConstraints={@ORM\UniqueConstraint(name="event", columns={"event","moduleName"})})
 * */
class NotificationEvent implements EntityInterface
{
    use BooleanList;

    /**
     * @var integer|null
     * @ORM\Id
     * @ORM\Column(type="integer", name="gibbonNotificationEventID", columnDefinition="INT(6) UNSIGNED ZEROFILL AUTO_INCREMENT")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var string|null
     * @ORM\Column(length=90)
     */
    private $event;

    /**
     * @var string|null
     * @ORM\Column(length=30, name="moduleName")
     */
    private $moduleName;

    /**
     * @var Module|null
     * @ORM\ManyToOne(targetEntity="Kookaburra\SystemAdmin\Entity\Module")
     * @ORM\JoinColumn(name="moduleID", referencedColumnName="gibbonModuleID", nullable=true)
     */
    private $module;

    /**
     * @var string|null
     * @ORM\Column(length=50, name="actionName")
     */
    private $actionName;

    /**
     * @var Action|null
     * @ORM\ManyToOne(targetEntity="Kookaburra\SystemAdmin\Entity\Action")
     * @ORM\JoinColumn(name="actionID", referencedColumnName="gibbonActionID", nullable=true)
     */
    private $action;

    /**
     * @var string|null
     * @ORM\Column(length=12, options={"default": "Core"})
     */
    private $type = 'Core';

    /**
     * @var array
     */
    private static $typeList = ['Core', 'Additional', 'CLI'];

    /**
     * @var string|null
     * @ORM\Column(options={"default": "All"})
     */
    private $scopes = 'All';

    /**
     * @var string|null
     * @ORM\Column(length=1, options={"default": "Y"})
     */
    private $active = 'Y';

    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="NotificationListener", mappedBy="event")
     */
    private $listeners;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return NotificationEvent
     */
    public function setId(?int $id): NotificationEvent
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getEvent(): ?string
    {
        return $this->event;
    }

    /**
     * @param string|null $event
     * @return NotificationEvent
     */
    public function setEvent(?string $event): NotificationEvent
    {
        $this->event = $event;
        return $this;
    }

    /**
     * @return string|null
     * @deprecated 10 Sep/2019  Use getModule()->getName()
     */
    public function getModuleName(): ?string
    {
        return $this->moduleName;
    }

    /**
     * @param string|null $moduleName
     * @return NotificationEvent
     * @deprecated 10 Sep/2019  Use getModule()->setName()
     */
    public function setModuleName(?string $moduleName): NotificationEvent
    {
        $this->moduleName = $moduleName;
        return $this;
    }

    /**
     * @return string|null
     * @deprecated 10 Sep/2019  Use getAction()->getName()
     */
    public function getActionName(): ?string
    {
        return $this->actionName;
    }

    /**
     * @param string|null $actionName
     * @return NotificationEvent
     * @deprecated 10 Sep/2019  Use getAction()->setName()
     */
    public function setActionName(?string $actionName): NotificationEvent
    {
        $this->actionName = $actionName;
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
     * @param string|null $type
     * @return NotificationEvent
     */
    public function setType(?string $type): NotificationEvent
    {
        $this->type = in_array($type, self::getTypeList()) ? $type : 'Core';
        return $this;
    }

    /**
     * @return string|null
     */
    public function getScopes(): ?string
    {
        return $this->scopes;
    }

    /**
     * @param string|null $scopes
     * @return NotificationEvent
     */
    public function setScopes(?string $scopes): NotificationEvent
    {
        $this->scopes = $scopes ?: 'All';
        return $this;
    }

    /**
     * @return string|null
     */
    public function getActive(): ?string
    {
        return $this->active;
    }

    /**
     * @param string|null $active
     * @return NotificationEvent
     */
    public function setActive(?string $active): NotificationEvent
    {
        $this->active = self::checkBoolean($active);
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
     * @return Collection
     */
    public function getListeners(): Collection
    {
        return $this->listeners;
    }

    /**
     * Listeners.
     *
     * @param Collection $listeners
     * @return NotificationEvent
     */
    public function setListeners(Collection $listeners): NotificationEvent
    {
        $this->listeners = $listeners;
        return $this;
    }

    /**
     * @return Module|null
     */
    public function getModule(): ?Module
    {
        return $this->module;
    }

    /**
     * Module.
     *
     * @param Module|null $module
     * @return NotificationEvent
     */
    public function setModule(?Module $module): NotificationEvent
    {
        $this->module = $module;
        return $this;
    }

    /**
     * @return Action|null
     */
    public function getAction(): ?Action
    {
        return $this->action;
    }

    /**
     * Action.
     *
     * @param Action|null $action
     * @return NotificationEvent
     */
    public function setAction(?Action $action): NotificationEvent
    {
        $this->action = $action;
        return $this;
    }
}