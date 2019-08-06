<?php
/**
 * Created by PhpStorm.
 *
 * bilby
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 20/07/2019
 * Time: 16:07
 */

namespace App\Controller\Modules;

use App\Entity\I18n;
use App\Manager\SystemAdmin\LanguageManager;
use App\Provider\ProviderFactory;
use Doctrine\DBAL\Driver\PDOException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SystemAdminController
 * @package App\Controller
 */
class SystemAdminController extends AbstractController
{
    /**
     * languageInstall
     * @param Request $request
     * @return RedirectResponse
     * @Route("/system/admin/language/manage/", name="system_admin_language_manage", methods={"POST"})
     * @Security("is_granted('ROLE_ACTION', ['/modules/System Admin/i18n_manage.php'])")
     */
    public function languageInstall(Request $request, LanguageManager $manager)
    {
        $i18n = ProviderFactory::getRepository(I18n::class)->find($request->request->get('gibboni18nID'));
        $url = '/?q=/modules/System Admin/i18n_manage.php';

        if (!$i18n instanceof I18n)
            return new RedirectResponse('/?q=/modules/System Admin/i18n_manage.php&return=error1');

        $installed = $manager->i18nFileInstall($this->getParameter('kernel.project_dir'), $i18n);

        if ($installed) {
            $i18n->setInstalled('Y');
            $i18n->setVersion($this->getParameter('version'));
            $em = $this->getDoctrine()->getManager();
            try {
                $em->persist($i18n);
                $em->flush();
                $updated = true;
            } catch (PDOException $e) {
                $updated = false;
            } catch (\PDOException $e) {
                $updated = false;
            }
        }

        if (!$installed)
            return new RedirectResponse($url. '&return=error3');
        if (!$updated)
            return new RedirectResponse($url. '&return=warning1');
        return new RedirectResponse($url. '&return=success0');
    }

    /**
     * check
     * @Route("/system/check/", name="system_check")
     * @Security("is_granted('ROLE_ACTION', ['/modules/System Admin/systemCheck.php'])")
     */
    public function check(){}
}