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


    public function setGuid(string $_guid)
    {
        $this->guid = $_guid;
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

    public function loadSystemSettings(Connection $pdo)
    {
        // System settings from gibbonSetting
        $sql = "SELECT name, value FROM gibbonSetting WHERE scope='System'";
        $result = $pdo->executeQuery(array(), $sql);

        while ($row = $result->fetch()) {
            $this->set($row['name'], $row['value']);
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

            return $retrieve(parent::get('guid'), $key, $default);
        }

        return (isset(parent::get('guid')[$key])) ? parent::get('guid')[$key] : $default;
    }

}