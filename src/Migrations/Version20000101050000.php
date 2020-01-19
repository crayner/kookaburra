<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Migrations\SqlLoad;
use App\Util\GlobalHelper;
use Doctrine\DBAL\Schema\Schema;
use Kookaburra\SystemAdmin\Entity\Module;
use Kookaburra\SystemAdmin\Entity\ModuleUpgrade;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Finder\Finder;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20000101050000 extends SqlLoad implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function getDescription() : string
    {
        return '';
    }

    /**
     * up
     * @param Schema $schema
     * @throws \Exception
     */
    public function up(Schema $schema) : void
    {
        $installation = $this->container->hasParameter('installation') ? $this->container->getParameter('installation') : [];
        if (isset($installation['demo']) && $installation['demo']) {
            $this->getSql('gibbon_demo.sql');

            $finder = new Finder();

            $bundles = $finder->directories()->in(__DIR__ . '/../../vendor/kookaburra')->depth(0);

            if ($finder->hasResults()) {
                foreach($bundles as $bundle) {
                    if (realpath($bundle->getPathname().'/src/Resources/migration/demo.sql') !== false)
                        $this->getSql($bundle->getPathname() . '/src/Resources/migration/demo.sql');
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

            $config = GlobalHelper::readKookaburraYaml();
            unset($config['parameters']['installation']['demo']);
            GlobalHelper::writeKookaburraYaml($config);
        }

    }

    public function down(Schema $schema) : void
    {

    }
}
