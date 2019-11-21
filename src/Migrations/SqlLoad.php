<?php
/**
 * Created by PhpStorm.
 *
 * Kookaburra
 *
 * (c) 2018 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 6/03/2019
 * Time: 09:56
 */
declare(strict_types=1);

namespace App\Migrations;

use App\Util\GlobalHelper;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Class SqlLoad
 * @package App\Migrations
 */
class SqlLoad extends AbstractMigration
{
    use SqlLoadTrait;
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
}