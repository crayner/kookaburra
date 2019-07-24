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
use App\Form\Installation\LanguageType;
use App\Manager\InstallationManager;
use App\Util\LocaleHelper;
use Gibbon\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class InstallController extends AbstractController
{
    /**
     * install
     *
     * @param int $step
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws Exception
     * @Route("/install/{step}/", name="instalation")
     */
    public function install(int $step)
    {
        switch ($step) {
            case 0:
                return $this->forward(InstallController::class."::installationCheck");
            default:
                throw new Exception('Installation step not found.');
        }
    }

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
            }
            return $this->redirectToRoute('installation_mysql');
        }
        return $manager->check($this->getParameter('systemRequirements'), $form);
    }

    /**
     * installationMySQLSettings
     *
     * @param Request $request
     * @Route("/installation/mysql/", name="installation_mysql")
     */
    public function installationMySQLSettings(Request $request, InstallationManager $manager, ValidatorInterface $validator)
    {

        dd($this);
    }
}