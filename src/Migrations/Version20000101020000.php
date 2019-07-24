<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Migrations\SqlLoad;
use App\Util\GlobalHelper;
use Doctrine\DBAL\Schema\Schema;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20000101020000 extends SqlLoad implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $installation = $this->container->hasParameter('installation') ? $this->container->getParameter('installation') : [];
        if (isset($installation['demo']) && $installation['demo']) {
            $this->getSql('gibbon_demo.sql');
            parent::up($schema);
            $config = GlobalHelper::readKookaburraYaml();
            unset($config['parameters']['installation']['demo']);
            GlobalHelper::writeKookaburraYaml($config);
        }

    }

    public function down(Schema $schema) : void
    {

    }
}
