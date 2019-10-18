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
final class Version20000101020000 extends SqlLoad implements ContainerAwareInterface
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
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function up(Schema $schema) : void
    {
        $this->getSql('gibbon_base.sql');

        $finder = new Finder();

        $bundles = $finder->directories()->in(__DIR__ . '/../../vendor/kookaburra')->depth(0);

        if ($finder->hasResults()) {
            foreach($bundles as $bundle) {
                if($bundle->getBasename() !== 'system-admin')
                    if (realpath($bundle->getPathname().'/src/Resources/migration/installation.sql') !== false)
                        $this->getSql($bundle->getPathname() . '/src/Resources/migration/installation.sql');
            }
        }

        $em = $this->container->get('doctrine.orm.default_entity_manager');
        foreach($em->getRepository(Module::class)->findAll() as $module)
        {
            $mu = new ModuleUpgrade();
            $mu->setModule($module)->setVersion('Installation');
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
