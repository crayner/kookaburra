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
use Symfony\Component\Yaml\Yaml;

class GibbonSession implements SessionInterface, \IteratorAggregate, \Countable, Session
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
        if ($name === 'guid')
        {
            return $this->guid();
        }
        return $this->getAttributeBag()->get($name, $default);
    }

    /**
     * {@inheritdoc}
     */
    public function set($name, $value = null)
    {
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
            $file = realpath(__dir__ . '/../../config/packages/gibbon.yaml');
            $data = Yaml::parse(file_get_contents($file));
            if (isset($data['parameters']['guid'])) {
                $this->setGuid($data['parameters']['guid']);
            } else {
                throw new \RuntimeException('This SessionManager must not used until the guid is correctly set.');
            }
            if ($this->isStarted() && !\array_key_exists($this->guid, $_SESSION)) {
                $_SESSION[$this->guid()] = [];
            }
        }

        return $this->guid;
    }

    /**
     * loadSystemSettings
     * @param Connection $pdo
     */
    public function loadSystemSettings(Connection $pdo)
    {
        // System settings from gibbonSetting
        $sql = "SELECT name, value FROM gibbonSetting WHERE scope='System'";
        $result = $pdo->executeQuery(array(), $sql);

        while ($row = $result->fetch()) {
            if (!empty($row['value'])) {
                $this->set($row['name'], $row['value']);
            }
        }
    }

    public function loadLanguageSettings(Connection $pdo)
    {
        // Language settings from gibboni18n
        $sql = "SELECT * FROM gibboni18n WHERE systemDefault='Y'";
        $result = $pdo->executeQuery(array(), $sql);

        while ($row = $result->fetch()) {
            $this->set('i18n', $row);
        }
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
}