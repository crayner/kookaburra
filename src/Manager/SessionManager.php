<?php
/**
 * Created by PhpStorm.
 *
 * bilby
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 26/06/2019
 * Time: 16:01
 */

namespace App\Manager;

use Gibbon\Contracts\Database\Connection;
use Symfony\Component\HttpFoundation\Session\Session;

class SessionManager extends Session
{
    /**
     * string
     */
    private	$guid ;

    /**
     * Gibbon\Contracts\Database\Connection
     */
    private	$pdo ;

    /**
     * @var array
     */
    private $guidData;

    /**
     * getSessionGuidData
     * @return array
     */
    private function getSessionGuidData(): array
    {
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

    public function setGuid(string $guid)
    {
        $this->guid = $guid;
    }

    /**
     * Set Database Connection
     * @version  v13
     * @since    v13
     * @param    Connection  $pdo
     */
    public function setDatabaseConnection(Connection $pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Return the guid string
     * TODO: Remove this
     *
     * @return	string
     */
    public function guid() {
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
     * Get an item from the session.
     *
     * @param	string	$key
     * @param	mixed	$default Define a value to return if the variable is empty
     *
     * @return	mixed
     */
    public function get($key, $default = null)
    {
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
     * Checks if one or more keys are present and not null.
     *
     * @param  string|array  $key
     * @return bool
     */
    public function has($keys)
    {
        $keys = is_array($keys)? $keys : [$keys];
        $has = !empty($keys);

        foreach ($keys as $key) {
            $has &= !empty($this->getSessionGuidData()[$key]);
        }

        return $has;
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

        foreach ($keys as $key) {
            $exists &= array_key_exists($key, $this->getSessionGuidData());
        }

        return $exists;
    }

    /**
     * Set a key / value pair or array of key / value pairs in the session.
     *
     * @param	string	$key
     * @param	mixed	$value
     */
    public function set($key, $value = null): void
    {
if ($key === 'absolutePath')
{
    dump($key,$value,debug_backtrace());
}
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
     * mergeLegacySession
     */
    public function mergeLegacySession(): void
    {
        $this->setSessionGuidData($this->getSessionGuidData());
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
}