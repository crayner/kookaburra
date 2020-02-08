<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * User: craig
 * Date: 21/11/2019
 * Time: 16:09
 */

namespace App\Migrations;


use App\Util\GlobalHelper;
use Doctrine\DBAL\Schema\Schema;

trait SqlLoadTrait
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
     * @var array
     */
    private $sqlContent = [];

    /**
     * getSql
     * @param string $source
     * @throws \Exception
     */
    public function getSql(string $source): void
    {
        if (is_file($source)) {
            $this->handle = fopen($source, "r");
        } elseif (is_file(__DIR__. '/'. $source)) {
            $this->handle = fopen(__DIR__ . '/' . $source, "r");
        }
        if(null === $this->handle)
            throw new \Exception('File ' .$source . 'not found.');

        $this->count = 0;

        $prefix = $this->getPrefix();

        while(($line = $this->getSqlLine()) !== false) {
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

    /**
     * @var string
     */
    private $prefix;

    /**
     * @return string
     */
    public function getPrefix(): string
    {
        if (null === $this->prefix) {
            $params = $this->connection->getParams();
            $this->setPrefix($params['driverOptions']['prefix']);
        }
        return $this->prefix;
    }

    /**
     * setPrefix
     * @param string $prefix
     * @return $this
     */
    public function setPrefix(string $prefix): self
    {
        $this->prefix = $prefix;
        return $this;
    }
}