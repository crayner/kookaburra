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
 * Class Version20000101020000
 * @package DoctrineMigrations
 */
final class Version20000101030000 extends SqlLoad implements ContainerAwareInterface
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
     * @throws \Doctrine\ORM\ORMException
     * @throws \Exception
     */
    public function up(Schema $schema) : void
    {
        $finder = new Finder();

        $bundles = $finder->directories()->in(__DIR__ . '/../../vendor/kookaburra')->depth(0);

        if ($finder->hasResults()) {
            foreach($bundles as $bundle) {
                if (realpath($bundle->getPathname().'/src/Resources/migration/foreign-constraint.sql') !== false)
                    $this->getSql($bundle->getPathname() . '/src/Resources/migration/foreign-constraint.sql');
            }
        }

        $em = $this->container->get('doctrine.orm.default_entity_manager');
        foreach($em->getRepository(Module::class)->findAll() as $module)
        {
            $mu = new ModuleUpgrade();
            $mu->setModule($module)->setVersion('Foreign Constraint');
            $em->persist($mu);
        }
        $em->flush();
    }

    public function down(Schema $schema) : void
    {
        $finder = new Finder();

        $bundles = $finder->directories()->in(__DIR__ . '/../../vendor/kookaburra')->depth(0);

        if ($finder->hasResults()) {
            foreach($bundles as $bundle) {
                    if (realpath($bundle->getPathname().'/src/Resources/migration/removal.sql') !== false)
                        $this->getSql($bundle->getPathname().'/src/Resources/migration/removal.sql');
            }
        }
    }
}
