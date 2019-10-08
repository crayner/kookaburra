<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Migrations\SqlLoad;
use Doctrine\DBAL\Schema\Schema;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20000101010000 extends SqlLoad implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->getSql('gibbon_base.sql');
        $this->getSql('action_base.sql');
        $this->getSql('module_base.sql');
        parent::up($schema);

//        $this->getSql('gibbon_demo.sql');
 //       parent::up($schema);
    }

    public function down(Schema $schema) : void
    {

    }
}
