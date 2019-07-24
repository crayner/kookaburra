<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
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
use App\Manager\InstallationManager;
use App\Util\LocaleHelper;
use Gibbon\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
        $form = $this->createForm(LanguageType::class, $i18n);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $errors = $validator->validate($form->get('code')->getData(), new \App\Validator\I18n());
            if (0 === count($errors)) {
                $manager->setLocale($form->get('code')->getData());
                return $this->redirectToRoute('installation_mysql');
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
    public function installationMySQLSettings(Request $request, InstallationManager $manager)
    {
        $mysql = new MySQLSettings();
        $message = null;

        $form = $this->createForm(MySQLType::class, $mysql);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
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
                'proceed' => false,
            ]
        );
    }

    /**
     * installationBuild
     * @param InstallationManager $manager
     * @param KernelInterface $kernel
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/installation/build/", name="installation_build")
     */
    public function installationBuild(InstallationManager $manager, KernelInterface $kernel)
    {
        return $manager->buildDatabase($kernel);
    }

    /**
     * installationSystem
     * @param Request $request
     * @param InstallationManager $manager
     * @Route("/installation/system/", name="installation_system")
     */
    public function installationSystem(Request $request, InstallationManager $manager)
    {
        dd($this);
    }
}