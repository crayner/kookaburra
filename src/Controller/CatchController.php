<?php
/**
 * Created by PhpStorm.
 *
 * bilby
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 6/07/2019
 * Time: 12:56
 */

namespace App\Controller;

use App\Container\Container;
use App\Container\ContainerManager;
use App\Container\Panel;
use App\Manager\GibbonManager;
use Gibbon\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CatchController
 * @package App\Controller
 */
class CatchController extends AbstractController
{
    /**
     * container
     * @Route("/container/")
     */
    public function container(ContainerManager $manager)
    {
        $container = new Container();
        $manager->setTranslationDomain('messages');

        $panel = new Panel();
        $panel->setName('One')->setContent($this->renderView('container_test.html.twig'));
        $container
            ->setTarget('containerTest')
            ->addPanel($panel)
        ;
        $panel = new Panel();
        $panel->setName('Two')->setContent($this->renderView('container_panel2.html.twig'));

        $manager->addContainer($container->addPanel($panel))->buildContainers();

        return $this->render('container.html.twig');
    }

    /**
     * index
     * @param Request $request
     * @throws Exception
     */
    public function index(Request $request, GibbonManager $manager)
    {
        $cwd = getcwd();
        $pathInfo = urldecode($request->getPathInfo());

        if (realpath(__DIR__ . '/../../Gibbon/' . $pathInfo) === false)
        {
            throw new Exception(sprintf('The file %s does not exist in the %s directory!', $pathInfo, realpath(__DIR__ . '/../../Gibbon')));
        }

        $manager->execute();

        $guid = $manager->getGuid();

        // To many fingers and no consistency in Gibbon connection naming of sql connection classes.

        $connection2 = $manager::getPDO();
        $connection = $manager::getConnection2();
        $pdo = $manager::getConnection();
        $gibbon = $manager::getGibbon();
        $container = $manager::getContainer();

        chdir(__DIR__.'/../../Gibbon/' . dirname($pathInfo));
        if ($request->getMethod() === 'POST')
        {
            include (__DIR__ . '/../../Gibbon/' . urldecode($request->getPathInfo()));
            die();
        }

        ob_start();
        include (__DIR__ . '/../../Gibbon/' . urldecode($request->getPathInfo()));
        $content = ob_get_clean();
        return new Response($content);
    }

    /**
     * moduleManager
     * @param string $moduleName
     * @param string $script
     * @param Request $request
     * @param GibbonManager $gibbonManager
     * @return Response|null
     * @throws Exception
     * @Route("/modules/{moduleName}/{script}", name="legacy_module_call")
     */
    public function moduleManager(string $moduleName, string $script, Request $request, GibbonManager $manager)
    {
        $cwd = getcwd();

        if (realpath(__DIR__ . '/../../Gibbon/modules/' . $moduleName . '/' . $script) === false)
        {
            throw new Exception(sprintf('The file %s does not exist in the %s directory!', $script, realpath(__DIR__ . '/../../Gibbon/modules/' . $moduleName)));
        }
        $manager->execute();

        $guid = $manager->getGuid();

        // To many fingers and no consistency in Gibbon connection naming of sql connection classes.

        $connection2 = $manager::getPDO();
        $connection = $manager::getConnection2();
        $pdo = $manager::getConnection();
        $gibbon = $manager::getGibbon();
        $container = $manager::getContainer();

        $modulePath = realpath( __DIR__.'/../../Gibbon/modules/' . $moduleName);
        chdir($modulePath);
        if ($request->getMethod() === 'POST')
        {
            include realpath($modulePath . '/' . $script);
            die();
        }

        ob_start();
        include realpath($modulePath . '/' . $script);
        $content = ob_get_clean();
        return new Response($content);
    }
}