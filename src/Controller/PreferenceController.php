<?php
/**
 * Created by PhpStorm.
 *
 * bilby
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 3/07/2019
 * Time: 16:51
 */

namespace App\Controller;

use App\Manager\GibbonManager;
use App\Manager\LegacyManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PreferenceController
 * @package App\Controller
 */
class PreferenceController extends AbstractController
{
    /**
     * preference
     * @Route("/preferences/", name="preferences")
     * @IsGranted("ROLE_USER")
     */
    public function preference(Request $request, GibbonManager $gibbonManager, LegacyManager $manager)
    {
        $error = $gibbonManager->execute();
        if ($error instanceof Response) {
            return $error;
        }

        $gibbonManager->injectAddress('preferences.php');
        $result = $manager->execute($request, $gibbonManager->getPage());

        if ($result instanceof Response){
            return $result;
        }

        return $this->render('legacy/index.html.twig');
    }
}