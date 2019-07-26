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


use App\Manager\GibbonManager;
use Gibbon\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CatchController extends AbstractController
{
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
}