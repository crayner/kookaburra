<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Migrations\SqlLoad;
use Doctrine\DBAL\Schema\Schema;
use Kookaburra\SystemAdmin\Entity\Module;
use Kookaburra\SystemAdmin\Entity\ModuleUpgrade;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Finder\Finder;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20000101000000 extends SqlLoad implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function getDescription() : string
    {
        return '';
    }

    /**
     * up
     * @param Schema $schema
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Exception
     */
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $finder = new Finder();

        $bundles = $finder->directories()->in(__DIR__ . '/../../vendor/kookaburra')->depth(0);

        if ($finder->hasResults()) {
            foreach($bundles as $bundle) {
                if (realpath($bundle->getPathname().'/src/Resources/migration/installation.sql') !== false)
                    $this->getSql($bundle->getPathname() . '/src/Resources/migration/installation.sql');
            }
        }
    }

    /**
     * down
     * @param Schema $schema
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Exception
     */
    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->getSql(realpath(__DIR__ . '/../../vendor/kookaburra/system-admin/src/Resources/migration/removal.sql'));

    }
}
