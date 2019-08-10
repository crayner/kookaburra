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

        dd($content, $forwardTo, $idName, $id, $view, $request);
/*    use Gibbon\Services\Format;

// Gibbon system-wide include
        require_once './gibbon.php';

// Setup the Page and Session objects
        $page = $container->get('page');
        $session = $container->get('session');

// Check to see if system settings are set from databases
        if (!$session->has('systemSettingsSet')) {
            getSystemSettings($guid, $connection2);
        }

// If still false, show warning, otherwise display page
        if (!$session->has('systemSettingsSet')) {
            exit(__('System Settings are not set: the system cannot be displayed'));
        }

        $address = $page->getAddress();

        if (empty($address)) {
            $page->addWarning(__('There is no content to display'));
        } elseif ($page->isAddressValid($address) == false) {
            $page->addError(__('Illegal address detected: access denied.'));
        } else {
            // Pass these globals into the script of the included file, for backwards compatibility.
            // These will be removed when we begin the process of ooifying action pages.
            $globals = [
                'guid'        => $guid,
                'gibbon'      => $gibbon,
                'version'     => $version,
                'pdo'         => $pdo,
                'connection2' => $connection2,
                'autoloader'  => $autoloader,
                'container'   => $container,
                'page'        => $page,
            ];

            if (is_file('./'.$address)) {
                $page->writeFromFile('./'.$address, $globals);
            } else {
                $page->writeFromFile('./error.php', $globals);
            }
        }

        $page->addHeadExtra($session->get('analytics'));
        $page->stylesheets->add('theme-dev', 'gibbon/core/theme.min.css');
        $page->stylesheets->add('core', 'gibbon/core/core.min.css', ['weight' => 10]);

        $page->addData([
            'isLoggedIn'                     => $session->has('username') && $session->has('gibbonRoleIDCurrent'),
            'username'                       => $session->get('username'),
            'gibbonThemeName'                => $session->get('gibbonThemeName'),
            'organisationName'               => $session->get('organisationName'),
            'organisationNameShort'          => $session->get('organisationNameShort'),
            'organisationAdministratorName'  => $session->get('organisationAdministratorName'),
            'organisationAdministratorEmail' => $session->get('organisationAdministratorEmail'),
            'organisationLogo'               => $session->get('organisationLogo'),
            'time'                           => Format::time(date('H:i:s')),
            'date'                           => Format::date(date('Y-m-d')),
            'rightToLeft'                    => $session->get('i18n')['rtl'] == 'Y',
        ]);

        echo $page->render('legacy/report.html.twig');
*/
    }
}