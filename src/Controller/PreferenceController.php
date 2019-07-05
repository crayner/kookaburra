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

use App\Entity\Person;
use App\Entity\SchoolYear;
use App\Manager\GibbonManager;
use App\Manager\LegacyManager;
use App\Manager\PreferencesManager;
use App\Provider\ProviderFactory;
use App\Security\PasswordManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
    public function preferences(Request $request, GibbonManager $gibbonManager, LegacyManager $manager)
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

    /**
     * process
     * @Route("/preferences/process/", name="preferences_process", methods={"POST"})
     * @IsGranted("ROLE_USER")
     */
    public function process(Request $request, PreferencesManager $manager)
    {
        return new RedirectResponse($manager->processPreferences($request, $this->getUser()));
    }

    /**
     * passwordProcess
     * @Route("/preferences/password/process/", name="preferences_password_process", methods={"POST"})
     * @IsGranted("ROLE_USER")
     */
    public function passwordProcess(Request $request, PasswordManager $manager)
    {
        return new RedirectResponse($manager->changePassword($request, $this->getUser()));
    }
}