<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * User: craig
 * Date: 22/07/2019
 * Time: 13:37
 */

namespace App\Controller;

use App\Entity\I18n;
use App\Form\Entity\MySQLSettings;
use App\Form\Installation\LanguageType;
use App\Form\Installation\MySQLType;
use App\Form\Installation\SystemType;
use App\Manager\InstallationManager;
use App\Provider\ProviderFactory;
use App\Twig\Sidebar\MySQLSettingWarning;
use App\Twig\SidebarContent;
use App\Util\LocaleHelper;
use Kookaburra\SystemAdmin\Form\Entity\SystemSettings;
use Kookaburra\SystemAdmin\Manager\LanguageManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class InstallController
 * @package App\Controller
 * @Route("/install", name="install__")
 */
class InstallController extends AbstractController
{
    /**
     * installationCheck
     *
     * @param Request $request
     * @Route("/installation/check/", name="installation_check")
     */
    public function installationCheck(Request $request, InstallationManager $manager, ValidatorInterface $validator)
    {
        $i18n = new I18n();
        $i18n->setCode(LocaleHelper::getDefaultLocale('en_GB'));
        $form = $this->createForm(LanguageType::class, $i18n, ['action' => $request->server->get('REQUEST_SCHEME') . '://' . $request->server->get('SERVER_NAME') . '/install/installation/check/']);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $errors = $validator->validate($form->get('code')->getData(), new \App\Validator\I18n());
            if (0 === count($errors)) {
                $manager->setLocale($form->get('code')->getData());
                $manager->setInstallationStatus('mysql');
                return $this->redirect($request->server->get('REQUEST_SCHEME') . '://' . $request->server->get('SERVER_NAME') . '/install/installation/mysql/');
            }
        }

        return $manager->check($this->getParameter('systemRequirements'), $form);
    }

    /**
     * installationMySQLSettings
     * @param Request $request
     * @param InstallationManager $manager
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     * @Route("/installation/mysql/", name="installation_mysql")
     */
    public function installationMySQLSettings(Request $request, InstallationManager $manager, SidebarContent $sidebar)
    {
        $mysql = new MySQLSettings();
        $message = null;
        $sidebar->addContent($x = new MySQLSettingWarning());

        $form = $this->createForm(MySQLType::class, $mysql, ['action' => $request->server->get('REQUEST_SCHEME') . '://' . $request->server->get('SERVER_NAME') . '/install/installation/mysql/']);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $manager->setInstallationStatus('build');
                return $manager->setMySQLSettings($form);
            } else {
                if (0 < count($form->getErrors()))
                {
                    $message['class'] = 'error';
                    $message['text'] = $form->getErrors()[0]->getMessage();
                }
            }
        }

        return $this->render('installation/mysql_settings.html.twig',
            [
                'form' => $form->createView(),
                'message' => $message,
                'sidebarOptions' => [
                    'proceed' => false,
                ]
            ]
        );
    }

    /**
     * installationBuild
     * @param InstallationManager $manager
     * @param KernelInterface $kernel
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     * @Route("/installation/build/", name="installation_build")
     */
    public function installationBuild(InstallationManager $manager, KernelInterface $kernel, Request $request)
    {
        return $manager->buildDatabase($kernel, $request);
    }

    /**
     * installationSystem
     * @param Request $request
     * @param InstallationManager $manager
     * @Route("/installation/system/", name="installation_system")
     */
    public function installationSystem(Request $request, InstallationManager $manager)
    {
        $settings = new SystemSettings($request);
        $settings->injectRequest($request);
        $message = null;

        $form = $this->createForm(SystemType::class, $settings, ['timezone' => $this->getParameter('timezone'), 'action' => $request->server->get('REQUEST_SCHEME') . '://' . $request->server->get('SERVER_NAME') . '/install/installation/system/']);

        $form->handleRequest($request);
        if ($form->isSubmitted())
        {
            if ($form->isValid())
            {
                $manager->setAdministrator($form);
                $manager->setSystemSettings($form);
            }
            return $this->redirect($request->server->get('REQUEST_SCHEME') . '://' . $request->server->get('SERVER_NAME') . '/install/installation/complete/');
        }

        return $this->render('installation/system_settings.html.twig',
            [
                'form' => $form->createView(),
                'message' => $message,
                'manager' => $manager,
            ]
        );
    }

    /**
     * installationComplete
     * @param InstallationManager $manager
     * @param LanguageManager $languageManager
     * @param KernelInterface $kernel
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     * @Route("/installation/complete/", name="installation_complete")
     */
    public function installationComplete(InstallationManager $manager, LanguageManager $languageManager, KernelInterface $kernel)
    {
        $i18n = ProviderFactory::getRepository(I18n::class)->findOneByCode($manager->getLocale());
        $languageManager->i18nFileInstall($i18n);
        $manager->moduleInstall($kernel);
        $manager->setInstallationStatus('complete');

        return $this->render('installation/complete.html.twig');
    }

    /**
     * installationComplete
     * @param InstallationManager $manager
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/update/", name="update")
     * @IsGranted("ROLE_SYSTEM_ADMIN")
     */
    public function update(){

    }
}