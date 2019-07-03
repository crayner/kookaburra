<?php
/**
 * Created by PhpStorm.
 *
 * bilby
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 3/07/2019
 * Time: 09:35
 */

namespace App\Session;

use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBagInterface;
use Symfony\Component\Security\Core\Exception\SessionUnavailableException;

class GibbonAttributeBag implements AttributeBagInterface, \IteratorAggregate, \Countable
{
    private $name = 'gibbon_attributes';
    private $storageKey;

    /**
     * @var string
     */
    private $guid;
    protected $attributes = [];

    /**
     * @param string $storageKey The key used to store attributes in the session
     */
    public function __construct(string $storageKey = '_gibbon_attributes')
    {
        $this->setGuid($storageKey);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * {@inheritdoc}
     */
    public function initialize(array &$attributes)
    {
        $this->attributes = &$attributes;
    }

    /**
     * {@inheritdoc}
     */
    public function getStorageKey()
    {
        return $this->storageKey;
    }

    /**
     * {@inheritdoc}
     */
    public function has($name)
    {
        if (! $this->exists($name))
        {
            return false;
        }
        $data = $this->get($name);

        if (is_bool($data))
        {
            return true;
        }

        return !empty($data);
    }

    /**
     * {@inheritdoc}
     */
    public function get($name, $default = null)
    {
        if (is_array($name))
        {
            return $this->getSubKey($name);
        }

        return \array_key_exists($name, $this->attributes) ? $this->attributes[$name] : $default;
    }

    /**
     * {@inheritdoc}
     */
    public function set($name, $value = null)
    {
        if ($name === 'guid') {
            $this->setGuid($value);
        } else {
            if (is_array($name)) {
                trigger_error('Never an array');
            } else {
                $this->attributes[$name] = $value;
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function all()
    {
        return $this->attributes;
    }

    /**
     * {@inheritdoc}
     */
    public function replace(array $attributes)
    {
        $this->attributes = [];
        foreach ($attributes as $key => $value) {
            $this->set($key, $value);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function remove($name)
    {
        $retval = null;
        if (\array_key_exists($name, $this->attributes)) {
            $retval = $this->attributes[$name];
            unset($this->attributes[$name]);
        }

        return $retval;
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        $return = $this->attributes;
        $this->attributes = [];

        return $return;
    }

    /**
     * Returns an iterator for attributes.
     *
     * @return \ArrayIterator An \ArrayIterator instance
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->attributes);
    }

    /**
     * Returns the number of attributes.
     *
     * @return int The number of attributes
     */
    public function count()
    {
        return \count($this->attributes);
    }

    /**
     * @return string
     */
    public function getGuid(): string
    {
        if (null === $this->guid)
        {
            throw new SessionUnavailableException('The session has not yet set the guid value.');
        }
        return $this->guid;
    }

    /**
     * Guid.
     *
     * @param string $guid
     * @return GibbonAttributeBag
     */
    public function setGuid(string $guid): GibbonAttributeBag
    {
        $this->guid = $guid;
        $this->storageKey = $guid;
        return $this;
    }

    /**
     * getSubKey
     * @param array $name
     * @param null $default
     */
    private function getSubKey(array $name, $default = null)
    {
        $data = $this->get($name[0], []);
        array_shift($name);

        foreach($name as $key)
        {
            if (\array_key_exists($key, $data))
            {
                $data = $data[$key];
            } else {
                return $default;
            }
        }

        return $data;
    }

    /**
     * exists
     * @param $name
     * @return bool
     */
    public function exists($name): bool
    {
        if (is_array($name)) {
            return $this->subKeyExists($name);
        }
        return \array_key_exists($name, $this->attributes);
    }

    /**
     * subKeyExists
     * @param array $name
     * @return bool
     */
    private function subKeyExists(array $name): bool
    {
        $data = $this->get($name[0], []);
        array_shift($name);

        foreach($name as $key)
        {
            if (\array_key_exists($key, $data))
            {
                $data = $data[$key];
            } else {
                return false;
            }
        }

        return true;
    }

    /**
     * Remove one or many items from the session.
     *
     * @param  string|array  $keys
     */
    public function forget($keys)
    {
        $keys = is_array($keys)? $keys : [$keys];

        foreach ($keys as $key) {
            $this->remove($key);
        }
    }

    /**
     * guid
     * @return string
     */
    private function guid()
    {
        return $this->getGuid();
    }
}