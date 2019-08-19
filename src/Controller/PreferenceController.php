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

use App\Container\Container;
use App\Container\ContainerManager;
use App\Container\Panel;
use App\Entity\Person;
use App\Entity\SchoolYear;
use App\Form\Entity\ResetPassword;
use App\Form\Security\ResetPasswordType;
use App\Manager\GibbonManager;
use App\Manager\LegacyManager;
use App\Manager\PreferencesManager;
use App\Provider\ProviderFactory;
use App\Security\PasswordManager;
use App\Util\SecurityHelper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
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
    public function preferences(Request $request, ContainerManager $manager)
    {
        $rp = new ResetPassword();
        $passwordForm = $this->createForm(ResetPasswordType::class, $rp);

        $passwordForm->handleRequest($request);
        if ($passwordForm->isSubmitted() && $passwordForm->isValid())
        {
            $user = $this->getUser();
            $user->changePassword($rp->getRaw());
            $this->addFlash('success', 'Your account has been successfully updated. You can now continue to use the system as per normal.');
        }

        $manager->setTranslationDomain('gibbon');
        $container = new Container();
        $passwordPanel = new Panel();
        $passwordPanel->setName('Reset Password')->setContent($this->renderView('modules/core/preferences_reset_password.html.twig',
            [
                'form' => $passwordForm->createView(),
                'passwordPolicy' => SecurityHelper::getPasswordPolicy(),
            ]
        ));
        $settingsPanel = new Panel();
        $settingsPanel->setName('Settings')->setContent('stuff');
        $container->addPanel($passwordPanel)->addPanel($settingsPanel)->setTarget('preferences');
        $manager->addContainer($container)->buildContainers();

        return $this->render('modules/core/preferences.html.twig');
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