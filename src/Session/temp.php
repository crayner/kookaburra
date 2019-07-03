<?php
/**
 * Created by PhpStorm.
 *
 * bilby
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 3/07/2019
 * Time: 10:03
 */

namespace App\Session;


class temp
{

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
     * Remove an item from the session, returning its value.
     *
     * @param  string  $key
     * @return mixed
     */
    public function remove($key)
    {
        $value = $this->get($key);
        unset($_SESSION[$this->guid][$key]);
        $data = $this->getSessionGuidData();
        unset($data[$key]);
        $this->setSessionGuidData($data);

        return $value;
    }

    /**
     * Checks if one or more keys are present and not null.
     *
     * @param  string|array  $keys
     * @return bool
     */
    public function has($keys): bool
    {
        $keys = is_array($keys)? $keys : [$keys];
        if ($keys === [])
        {
            return false;
        }
        if (!$this->exists($keys))
        {
            return false;
        }

        $data = $this->getSessionGuidData();
        foreach ($keys as $key) {
            if (empty($data[$key]) && $data[$key] !== false)
            {
                return false;
            }
        }

        return true;
    }

    /**
     * Checks if one or more keys exist.
     *
     * @param  string|array  $keys
     * @return bool
     */
    public function exists($keys)
    {
        $keys = is_array($keys)? $keys : [$keys];
        $exists = !empty($keys);

        $data = $this->getSessionGuidData();
        foreach ($keys as $key) {
            $exists &= isset($data[$key]);
        }

        return $exists;
    }

    /**
     * Get an item from the session.
     *
     * @param	string	$key
     * @param	mixed	$default Define a value to return if the variable is empty
     *
     * @return	mixed
     */
    public function get($key, $default = null)
    {
        if ($key === 'guid')
        {
            return $this->guid();
        }

        if (is_array($key)) {
            // Fetch a value from multi-dimensional array with an array of keys
            $retrieve = function($array, $keys, $default) {
                foreach($keys as $key) {
                    if (!isset($array[$key])) return $default;
                    $array = $array[$key];
                }
                return $array;
            };

            return $retrieve($this->getSessionGuidData(), $key, $default);
        }

        return (isset($this->getSessionGuidData()[$key])) ? $this->getSessionGuidData()[$key] : $default;
    }

    /**
     * Set a key / value pair or array of key / value pairs in the session.
     *
     * @param	string	$key
     * @param	mixed	$value
     */
    public function set($key, $value = null): void
    {
        $name = $key;
        $keyValuePairs = is_array($key)? $key : [$key => $value];

        $data = $this->getSessionGuidData();

        foreach ($keyValuePairs as $key => $value) {
            $data[$key] = $value;
            if (isset($_SESSION[$this->guid()][$key]))
            {
                unset($_SESSION[$this->guid()][$key]);
            }
        }

        $this->setSessionGuidData($data);
    }

    /**
     * getSessionGuidData
     * @return array
     */
    private function getSessionGuidData(): array
    {
        if (null === $this->guid)
        {
            $this->guid();
        }
        if (null !== $this->guidData && is_array($this->guidData))
        {
            if (!isset($_SESSION[$this->guid()]))
            {
                $_SESSION[$this->guid()] = [];
            }
            return array_merge($this->guidData, $_SESSION[$this->guid()]);

        }
        return $this->guidData = parent::has('guid') ? parent::get('guid') : [];
    }

    /**
     * setSessionGuidData
     * @param array $data
     * @return SessionManager
     */
    private function setSessionGuidData(array $data = []): self
    {
        if (null !== $this->guid) {
            if (!isset($_SESSION[$this->guid()]))
            {
                $_SESSION[$this->guid()] = [];
            }

            $this->guidData = array_merge($data, $_SESSION[$this->guid()]);

            parent::set($this->guid(), $this->guidData);

            $_SESSION[$this->guid()] = $this->guidData;
        }

        return $this;
    }
}