<?php
/**
 * Created by PhpStorm.
 *
* Gibbon-Mobile
 *
 * (c) 2018 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 6/03/2019
 * Time: 09:56
 */
declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Class SqlLoad
 * @package App\Migrations
 */
class SqlLoad extends AbstractMigration
{
    /**
     * @var file pointer resource|null
     */
    private $handle;

    /**
     * @var integer
     */
    private $count;

    /**
     * up
     * @param Schema $schema
     * @throws \Doctrine\DBAL\DBALException
     */
    public function up(Schema $schema) : void
    {
    }

    public function down(Schema $schema) : void
    {}

    /**
     * getSql
     * @param string $source
     * @throws \Exception
     */
    public function getSql(string $source): void
    {
        if (file_exists(__DIR__. '/'. $source))
            $this->handle = fopen(__DIR__. '/'. $source, "r");
        elseif (file_exists($source))
            $this->handle = fopen($source, "r");
        if(null === $this->handle)
            throw new \Exception('File ' .$source . 'not found.');

        $this->count = 0;

        while(($line = $this->getSqlLine()) !== false)
        {
            $this->addSql($line);
        }

    }

    /**
     * getSqlLine
     * @return bool|string
     * @throws \Exception
     */
    private function getSqlLine()
    {
        if ($this->handle) {
            $sql = '';
            while (($line = fgets($this->handle)) !== false) {
                $line = trim($line, "\n\r");
                if (empty($line))
                    continue;
                if (mb_strpos($line, '--') === 0)
                    continue;
                if (mb_strpos($line, '#') === 0)
                    continue;
                $sql .= $line;
                try {
                    if (mb_strpos($line, ';', -1) !== false) {
                        $this->count++;
                        return $sql;
                    }
                } catch (\Exception $e) {
                    echo $line."\r\n";
                    echo strlen($line)."\r\n";
                    throw $e;
                }
            }
            fclose($this->handle);
            return false;
        }
        return false;
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }
}