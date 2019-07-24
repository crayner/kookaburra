<?php
/**
 * Created by PhpStorm.
 *
 * bilby
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 2/07/2019
 * Time: 16:46
 */

namespace App\Manager;

use Gibbon\Database\Connection;
use Gibbon\Database\Result;
use Symfony\Component\Yaml\Yaml;

class LegacyConnectionFactory
{
    /**
     * @var \PDO
     */
    private $pdo;

    /**
     * @var array
     */
    private $config = [];

    /**
     * @var string|null
     */
    private	$error;

    /**
     * @var bool
     */
    private	$success = false ;

    /**
     * LegacyConnectionFactory constructor.
     * @param string|null $message
     */
    public function __construct(string $message = null)
    {
        if (realpath(__DIR__ . '/../../config/packages/kookaburra.yaml')) {
            $data = Yaml::parse(file_get_contents(__DIR__ . '/../../config/packages/kookaburra.yaml'));

            $data = $data['parameters'];
            $data['databasePort'] = isset($data['databasePort']) ? $data['databasePort'] : null;

            $this->config = $data;

            return $this->generateConnection($data['databaseServer'], $data['databaseName'], $data['databaseUsername'], $data['databasePassword'], $data['databasePort'], $message);
        }
        return null;
    }


    /**
     * generate Connection
     *
     * @param	string	Server Address:port
     * @param	string	Database Name
     * @param	string	User Name
     * @param	string	Password
     * @param	string	error Message
     *
     * @return	Object	PDO Connection
     */
    private function generateConnection($databaseServer, $databaseName, $databaseUsername, $databasePassword, $databasePort = NULL, $message = NULL)
    {
        $this->pdo = NULL;
        $this->success = false;
        try {
            $dns = "mysql:host=$databaseServer;";
            $dns .= (!empty($databasePort))? "port=$databasePort;" : '';
            $dns .= (!empty($databaseName))? "dbname=$databaseName;" : '';
            $dns .= "charset=utf8";

            $this->pdo = new \PDO($dns, $databaseUsername, $databasePassword );
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
            $this->pdo->setAttribute(\PDO::ATTR_STATEMENT_CLASS, [Result::class]);
            $this->setSQLMode();
            $this->success = true;
        } catch(\PDOException $e) {
            $this->error = $e->getMessage();
            trigger_error(($message !== NULL) ? $message : $this->error, E_USER_WARNING);
        }

        return $this;
    }

    /**
     * @var Connection
     */
    private $connection;

    /**
     * createConnection
     * @return null|Connection
     */
    public function createConnection(): ?Connection
    {
        if (null === $this->connection && null !== $this->getPDO())
        {
            $this->connection = new Connection($this->getPDO(), $this->config);
        }

        return $this->connection;
    }

    /**
     * getPDO
     * @return null|\PDO
     */
    public function getPDO(): ?\PDO
    {
        return $this->pdo;
    }

    /**
     * Set SQL Mode
     */
    private function setSQLMode()
    {
        $version = $this->getVersion();
        if ($version > '5.7')  //Default for 5.7.x is STRICT_ALL_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_ZERO_DATE,NO_ZERO_IN_DATE,NO_AUTO_CREATE_USER
            $result = $this->pdo->prepare("SET SESSION `sql_mode` = ''");
        elseif ($version > '5.6')  // Default for 5.6.x is NO_ENGINE_SUBSTITUTION
            $result = $this->pdo->prepare("SET SESSION `sql_mode` = ''");
        else // Default for < 5.6 is ''
            $result = $this->pdo->prepare("SET SESSION `sql_mode` = ''");
        $result->execute(array());
    }

    /**
     * Get Version
     *
     * @return	string	Version
     */
    public function getVersion()
    {
        return $this->pdo->query("SELECT VERSION()")->fetchColumn();
    }
}