<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 10/08/2019
 * Time: 08:57
 */

namespace App\Controller;

use App\Manager\GibbonManager;
use Gibbon\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ReportController
 * @package App\Controller
 */
class ReportController extends AbstractController
{
    /**
     * index
     * @Route("/report/{forwardTo}/{idName}/{id}/{view}/generate/", name="report_generate")
     */
    public function index(string $forwardTo, string $idName, int $id, string $view, Request $request, GibbonManager $manager)
    {
        $forwardTo = urldecode($forwardTo);
        if (realpath(__DIR__ . '/../../Gibbon' . $forwardTo) === false)
        {

            return $this->render('legacy/error.html.twig', [
                'extendedError' => 'The file "{path}" does not exist in the "{dir}" directory!',
                'extendedParams' => [
                    '{path}' => $forwardTo,
                    '{dir}' => realpath(__DIR__ . '/../../Gibbon')
                ],
            ]);
        }

        $this->denyAccessUnlessGranted(strpos($forwardTo, '.php') !== false ? 'ROLE_ACTION' : 'ROLE_ROUTE', [$forwardTo]);

        $manager->execute();

        $guid = $manager->getGuid();

        // To many fingers and no consistency in Gibbon connection naming of sql connection classes.

        $connection2 = $manager::getPDO();
        $connection = $manager::getConnection2();
        $pdo = $manager::getConnection();
        $gibbon = $manager::getGibbon();
        $container = $manager::getContainer();

        $dir = getcwd();
        chdir(realpath(__DIR__.'/../../Gibbon' . dirname($forwardTo)));

        $_GET[$idName] = $id;
        ob_start();
        include (__DIR__ . '/../../Gibbon' . $forwardTo);
        $content = ob_get_clean();
        chdir($dir);

        $content = str_replace('./themes/Default/img/print.png', '/themes/Default/img/print.png', $content);

        return $this->render('default/report.html.twig',
            [
                'content' => $content,
            ]
        );
    }
}