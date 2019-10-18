<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Migrations\SqlLoad;
use Doctrine\DBAL\Schema\Schema;


/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20000101000000 extends SqlLoad
{
    /**
     * up
     * @param Schema $schema
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Exception
     * @throws \Exception
     */
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->getSql(realpath(__DIR__  . '/../../vendor/kookaburra/system-admin/src/Resources/migration/installation.sql'));

    }

    /**
     * down
     * @param Schema $schema
     * @throws \Doctrine\DBAL\DBALException
     */
    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->getSql(realpath(__DIR__ . '/../../vendor/kookaburra/system-admin/src/Resources/migration/removal.sql'));

    }
}
