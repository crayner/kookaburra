<?php
/**
 * Created by PhpStorm.
 *
 * bilby
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 3/07/2019
 * Time: 09:58
 */
namespace App\Session;

use App\Entity\I18n;
use Kookaburra\SystemAdmin\Entity\Module;
use Kookaburra\SystemAdmin\Entity\Role;
use Kookaburra\SchoolAdmin\Entity\AcademicYear;
use App\Entity\Setting;
use App\Provider\ProviderFactory;
use Gibbon\Contracts\Database\Connection;
use Gibbon\Contracts\Services\Session;
use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBagInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionBagProxy;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;
use Symfony\Component\HttpFoundation\Session\Storage\SessionStorageInterface;

class GibbonSession extends \Gibbon\Session implements SessionInterface, \IteratorAggregate, \Countable, Session
{
    protected $storage;

    private $flashName;
    private $attributeName;
    private $data = [];
    private $usageIndex = 0;
    private $guid;

    /**
     * @param SessionStorageInterface $storage A SessionStorageInterface instance
     * @param AttributeBagInterface $attributes An AttributeBagInterface instance, (defaults null for default AttributeBag)
     * @param FlashBagInterface $flashes A FlashBagInterface instance (defaults null for default FlashBag)
     */
    public function __construct(SessionStorageInterface $storage = null, AttributeBagInterface $attributes = null, FlashBagInterface $flashes = null)
    {
        $this->storage = $storage ?: new NativeSessionStorage();

        $attributes = $attributes ?: new GibbonAttributeBag($this->guid());
        $flashes = $flashes ?: new FlashBag();

        $this->attributeName = $attributes->getName();
        $this->registerBag($attributes);

        $this->flashName = $flashes->getName();
        $this->registerBag($flashes);

        $this->guid();
    }

    /**
     * {@inheritdoc}
     */
    public function start()
    {
        return $this->storage->start();
    }

    /**
     * {@inheritdoc}
     */
    public function has($name)
    {
        return $this->getAttributeBag()->has($name);
    }

    /**
     * {@inheritdoc}
     */
    public function get($name, $default = null)
    {
        switch ($name)
        {
            case 'guid':
                return $this->guid();
                break;
            case 'academicYear':
                if (null === $this->getAttributeBag()->get($name, null))
                {
                    $id = $this->getAttributeBag()->get('AcademicYearID', 0);
                    $schoolYear = ProviderFactory::getRepository(AcademicYear::class)->find($id) ?: ProviderFactory::getRepository(AcademicYear::class)->findOneByStatus('Current');
                    $this->set('academicYear', $schoolYear);
                    $this->set('AcademicYearID', $schoolYear->getId());
                    return $this->getAttributeBag()->get($name, null);
                }
                break;
        }
        $result = $this->getAttributeBag()->get($name, $default);
        if ('' === $result || null === $result)
            return $default;
        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function set($name, $value = null)
    {
        if ($name === 'AcademicYearID' && (null === $value || '' === $value || 0 === $value))
        {
            $schoolYear = ProviderFactory::getRepository(AcademicYear::class)->findOneBy(['status' => 'Current']);
            $value = $schoolYear ? $schoolYear->getId() : null;
        }
        $this->getAttributeBag()->set($name, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function all()
    {
        return $this->getAttributeBag()->all();
    }

    /**
     * {@inheritdoc}
     */
    public function replace(array $attributes)
    {
        $this->getAttributeBag()->replace($attributes);
    }

    /**
     * {@inheritdoc}
     */
    public function remove($name)
    {
        return $this->getAttributeBag()->remove($name);
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        $this->getAttributeBag()->clear();
    }

    /**
     * {@inheritdoc}
     */
    public function isStarted()
    {
        return $this->storage->isStarted();
    }

    /**
     * Returns an iterator for attributes.
     *
     * @return \ArrayIterator An \ArrayIterator instance
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->getAttributeBag()->all());
    }

    /**
     * Returns the number of attributes.
     *
     * @return int The number of attributes
     */
    public function count()
    {
        return \count($this->getAttributeBag()->all());
    }

    /**
     * @return int
     *
     * @internal
     */
    public function getUsageIndex()
    {
        return $this->usageIndex;
    }

    /**
     * @return bool
     *
     * @internal
     */
    public function isEmpty()
    {
        if ($this->isStarted()) {
            ++$this->usageIndex;
        }
        foreach ($this->data as &$data) {
            if (!empty($data)) {
                return false;
            }
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function invalidate($lifetime = null)
    {
        $this->storage->clear();

        return $this->migrate(true, $lifetime);
    }

    /**
     * {@inheritdoc}
     */
    public function migrate($destroy = false, $lifetime = null)
    {
        return $this->storage->regenerate($destroy, $lifetime);
    }

    /**
     * {@inheritdoc}
     */
    public function save()
    {
        $this->storage->save();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->storage->getId();
    }

    /**
     * {@inheritdoc}
     */
    public function setId($id)
    {
        if ($this->storage->getId() !== $id) {
            $this->storage->setId($id);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->storage->getName();
    }

    /**
     * {@inheritdoc}
     */
    public function setName($name)
    {
        $this->storage->setName($name);
    }

    /**
     * {@inheritdoc}
     */
    public function getMetadataBag()
    {
        ++$this->usageIndex;

        return $this->storage->getMetadataBag();
    }

    /**
     * {@inheritdoc}
     */
    public function registerBag(SessionBagInterface $bag)
    {
        $this->storage->registerBag(new SessionBagProxy($bag, $this->data, $this->usageIndex));
    }

    /**
     * {@inheritdoc}
     */
    public function getBag($name)
    {
        $bag = $this->storage->getBag($name);

        return method_exists($bag, 'getBag') ? $bag->getBag() : $bag;
    }

    /**
     * Gets the flashbag interface.
     *
     * @return FlashBagInterface
     */
    public function getFlashBag()
    {
        return $this->getBag($this->flashName);
    }

    /**
     * Gets the attributebag interface.
     *
     * Note that this method was added to help with IDE autocompletion.
     *
     * @return AttributeBagInterface
     */
    private function getAttributeBag()
    {
        return $this->getBag($this->attributeName);
    }

    /**
     * setGuid
     * @param string $guid
     */
    public function setGuid(string $guid)
    {
        $this->guid = $guid;
        if ($this->attributeName) {
            $this->getAttributeBag()->setGuid($guid);
        }
    }

    /**
     * Set Database Connection
     * @param Connection $pdo
     * @since    v13
     * @version  v13
     */
    public function setDatabaseConnection(Connection $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Return the guid string
     * TODO: Remove this
     *
     * @return    string
     */
    public function guid()
    {
        if (null === $this->guid) {
            $this->guid = guid();
;            if ($this->isStarted() && !\array_key_exists($this->guid, $_SESSION)) {
                $_SESSION[$this->guid()] = [];
            }
        }

        return $this->guid;
    }

    /**
     * loadSystemSettings
     */
    public function loadSystemSettings()
    {
        // System settings from gibbonSetting
        $results = ProviderFactory::getRepository(Setting::class)->findByScope('System');

        foreach($results as $row) {
            if (!empty($row->getValue())) {
                $this->set($row->getName(), $row->getValue());
            }
        }
    }

    /**
     * loadLanguageSettings
     */
    public function loadLanguageSettings()
    {
        // Language settings from i18n
        $results = ProviderFactory::getRepository(I18n::class)->findOneBySystemDefault('Y');

        $this->set('i18n', $results->__toArray());
    }

    /**
     * exists
     * @param $name
     * @return mixed
     */
    public function exists($name)
    {
        return $this->getAttributeBag()->exists($name);
    }

    /**
     * Remove one or many items from the session.
     *
     * @param  string|array  $keys
     */
    public function forget($keys)
    {
        $this->getAttributeBag()->forget($keys);
    }

    /**
     * Cache translated FastFinder actions to allow searching actions with the current locale
     * @version  v13
     * @since    v13
     * @param    string  $roleID
     */
    public function cacheFastFinderActions($roleID) {

        // Get the accessible actions for the current user
        $result = ProviderFactory::create(Module::class)->selectModulesByRole($roleID, false);

        if (count($result) > 0) {
            $actions = [];
            // Translate the action names
            foreach($result as $row)
            {
                $row['name'] = __($row['name']);
                $actions[] = $row;
            }

            // Cache the resulting set of translated actions
            $this->set('fastFinderActions', $actions);
        }
        return $actions;
    }

    /**
     * addFlash
     * @param string $type
     * @param string $message
     */
    public function addFlash(string $type, string $message)
    {
        $this->getFlashBag()->add($type, $message);
    }
}