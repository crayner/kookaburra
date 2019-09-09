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

use App\Container\Container;
use App\Container\ContainerManager;
use App\Container\Panel;
use App\Entity\I18n;
use App\Entity\Setting;
use App\Form\Modules\SystemAdmin\EmailSettingsType;
use App\Form\Modules\SystemAdmin\GoogleIntegationType;
use App\Form\Modules\SystemAdmin\LocalisationSettingsType;
use App\Form\Modules\SystemAdmin\MiscellaneousSettingsType;
use App\Form\Modules\SystemAdmin\OrganisationSettingsType;
use App\Form\Modules\SystemAdmin\PaypalSettingsType;
use App\Form\Modules\SystemAdmin\SecuritySettingsType;
use App\Form\Modules\SystemAdmin\SMSSettingsType;
use App\Form\Modules\SystemAdmin\SystemSettingsType;
use App\Manager\SystemAdmin\GoogleSettingManager;
use App\Manager\SystemAdmin\LanguageManager;
use App\Manager\SystemAdmin\MailerSettingsManager;
use App\Provider\ProviderFactory;
use Doctrine\DBAL\Driver\PDOException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class SystemAdminController
 * @package App\Controller
 * @Route("/system_admin", name="system_admin__")
 */
class SystemAdminController extends AbstractController
{
    /**
     * languageInstall
     * @param Request $request
     * @return RedirectResponse
     * @Route("/language/manage/", name="language_manage", methods={"POST"})
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
     * systemSettings
     * @param Request $request
     * @Route("/system/{tabName}/settings/", name="system_settings")
     */
    public function systemSettings(Request $request, ContainerManager $manager, TranslatorInterface $translator, string $tabName = 'System')
    {
        $settingProvider = ProviderFactory::create(Setting::class);
        $container = new Container();
        // System Settings
        $form = $this->createForm(SystemSettingsType::class, null, ['action' => $this->generateUrl('system_admin__system_settings', ['tabName' => 'System']) ]);

        if ($tabName === 'System' && $request->getContentType() === 'json') {
            $data = [];
            try {
                $data['errors'] = $settingProvider->handleSettingsForm($form, $request, $translator);
            } catch (\Exception $e) {
                $data['errors'][] = ['class' => 'error', 'message' => $translator->trans('Your request failed due to a database error.')];
            }

            $manager->singlePanel($form->createView());
            $data['form'] = $manager->getFormFromContainer('formContent', 'single');

            return new JsonResponse($data,200);
        }

        $panel = new Panel('System');
        $container->addForm('System', $form->createView())->setTarget('formContent')->addPanel($panel);

        // Organisation Settings
        $form = $this->createForm(OrganisationSettingsType::class, null,
            [
                'action' => $this->generateUrl('system_admin__system_settings', ['tabName' => 'Organisation']),
                'attr' => [
                    'encType' => 'multipart/form-data',
                ],
            ]
        );

        if ($tabName === 'Organisation' && $request->getContentType() === 'json') {
            $data = [];
            try {
                $data['errors'] = $settingProvider->handleSettingsForm($form, $request, $translator);
            } catch (\Exception $e) {
                $data['errors'][] = ['class' => 'error', 'message' => $translator->trans('Your request failed due to a database error.') . ' ' . $e->getMessage()];
            }

            $manager->singlePanel($form->createView());
            $data['form'] = $manager->getFormFromContainer('formContent', 'single');

            return new JsonResponse($data,200);
        }

        $panel = new Panel('Organisation');
        $container->addForm('Organisation', $form->createView())->addPanel($panel)->setSelectedPanel($tabName);

        // Security Settings
        $form = $this->createForm(SecuritySettingsType::class, null,
            [
                'action' => $this->generateUrl('system_admin__system_settings', ['tabName' => 'Security']),
            ]
        );

        if ($tabName === 'Security' && $request->getContentType() === 'json') {
            $data = [];
            try {
                $data['errors'] = $settingProvider->handleSettingsForm($form, $request, $translator);
            } catch (\Exception $e) {
                $data['errors'][] = ['class' => 'error', 'message' => $translator->trans('Your request failed due to a database error.') . ' ' . $e->getMessage()];
            }

            $manager->singlePanel($form->createView());
            $data['form'] = $manager->getFormFromContainer('formContent', 'single');

            return new JsonResponse($data,200);
        }

        $panel = new Panel('Security');
        $container->addForm('Security', $form->createView())->addPanel($panel)->setSelectedPanel($tabName);

        // Localisation
        $form = $this->createForm(LocalisationSettingsType::class, null,
            [
                'action' => $this->generateUrl('system_admin__system_settings', ['tabName' => 'Localisation']),
            ]
        );

        if ($tabName === 'Localisation' && $request->getContentType() === 'json') {
            $data = [];
            try {
                $data['errors'] = $settingProvider->handleSettingsForm($form, $request, $translator);
            } catch (\Exception $e) {
                $data['errors'][] = ['class' => 'error', 'message' => $translator->trans('Your request failed due to a database error.') . ' ' . $e->getMessage()];
            }

            $manager->singlePanel($form->createView());
            $data['form'] = $manager->getFormFromContainer('formContent', 'single');

            return new JsonResponse($data,200);
        }

        $panel = new Panel('Localisation');
        $container->addForm('Localisation', $form->createView())->addPanel($panel)->setSelectedPanel($tabName);

        // Miscellaneous
        $form = $this->createForm(MiscellaneousSettingsType::class, null,
            [
                'action' => $this->generateUrl('system_admin__system_settings', ['tabName' => 'Miscellaneous']),
            ]
        );

        if ($tabName === 'Miscellaneous' && $request->getContentType() === 'json') {
            $data = [];
            try {
                $data['errors'] = $settingProvider->handleSettingsForm($form, $request, $translator);
            } catch (\Exception $e) {
                $data['errors'][] = ['class' => 'error', 'message' => $translator->trans('Your request failed due to a database error.') . ' ' . $e->getMessage()];
            }

            $manager->singlePanel($form->createView());
            $data['form'] = $manager->getFormFromContainer('formContent', 'single');

            return new JsonResponse($data,200);
        }

        $panel = new Panel('Miscellaneous');
        $container->addForm('Miscellaneous', $form->createView())->addPanel($panel)->setSelectedPanel($tabName);

        // Finally Finished
        $manager->addContainer($container)->buildContainers();

        return $this->render('modules/system_admin/system_settings.html.twig');
    }

    /**
     * thirdParty
     * @param Request $request
     * @param ContainerManager $manager
     * @param TranslatorInterface $translator
     * @param string $tabName
     * @Route("/third_party/{tabName}", name="third_party")
     * @IsGranted("ROLE_ROUTE"))
     */
    public function thirdParty(Request $request, ContainerManager $manager, TranslatorInterface $translator, string $tabName = 'Google')
    {
        $settingProvider = ProviderFactory::create(Setting::class);
        $container = new Container();
        $container->setTarget('formContent')->setSelectedPanel($tabName)->setApplication('ThirdParty');

        // Google
        $form = $this->createForm(GoogleIntegationType::class, null, ['action' => $this->generateUrl('system_admin__third_party', ['tabName' => 'Google']) ]);

        if ($tabName === 'Google' && $request->getContentType() === 'json') {
            $data = [];
            try {
                $data['errors'] = $settingProvider->handleSettingsForm($form, $request, $translator);
                $gm = new GoogleSettingManager();
                $data['errors'][] = $gm->handleGoogleSecretsFile($form, $request, $translator);
            } catch (\Exception $e) {
                $data['errors'][] = ['class' => 'error', 'message' => $translator->trans('Your request failed due to a database error.') . ' ' .$e->getMessage() ];
            }

            $form = $this->createForm(GoogleIntegationType::class, null, ['action' => $this->generateUrl('system_admin__third_party', ['tabName' => 'Google']) ]);
            $manager->singlePanel($form->createView());
            $data['form'] = $manager->getFormFromContainer('formContent', 'single');

            return new JsonResponse($data,200);
        }

        $panel = new Panel('Google');
        $container->addForm('Google', $form->createView())->addPanel($panel);

        // PayPal
        $form = $this->createForm(PaypalSettingsType::class, null, ['action' => $this->generateUrl('system_admin__third_party', ['tabName' => 'PayPal']) ]);

        if ($tabName === 'PayPal' && $request->getContentType() === 'json') {
            $data = [];
            try {
                $data['errors'] = $settingProvider->handleSettingsForm($form, $request, $translator);
            } catch (\Exception $e) {
                $data['errors'][] = ['class' => 'error', 'message' => $translator->trans('Your request failed due to a database error.')];
            }

            $form = $this->createForm(PaypalSettingsType::class, null, ['action' => $this->generateUrl('system_admin__third_party', ['tabName' => 'PayPal']) ]);
            $manager->singlePanel($form->createView());
            $data['form'] = $manager->getFormFromContainer('formContent', 'single');

            return new JsonResponse($data,200);
        }

        $panel = new Panel('PayPal');
        $container->addForm('PayPal', $form->createView())->addPanel($panel);

        // SMS
        $form = $this->createForm(SMSSettingsType::class, null, ['action' => $this->generateUrl('system_admin__third_party', ['tabName' => 'SMS']) ]);

        if ($tabName === 'SMS' && $request->getContentType() === 'json') {
            $data = [];
            try {
                $data['errors'] = $settingProvider->handleSettingsForm($form, $request, $translator);
            } catch (\Exception $e) {
                $data['errors'][] = ['class' => 'error', 'message' => $translator->trans('Your request failed due to a database error.')];
            }

            $form = $this->createForm(SMSSettingsType::class, null, ['action' => $this->generateUrl('system_admin__third_party', ['tabName' => 'SMS']) ]);
            $manager->singlePanel($form->createView());
            $data['form'] = $manager->getFormFromContainer('formContent', 'single');

            return new JsonResponse($data,200);
        }

        $panel = new Panel('SMS');
        $container->addForm('SMS', $form->createView())->addPanel($panel);

        // E-Mail
        $form = $this->createForm(EmailSettingsType::class, null, ['action' => $this->generateUrl('system_admin__third_party', ['tabName' => 'E-Mail']) ]);

        if ($tabName === 'E-Mail' && $request->getContentType() === 'json') {
            $data = [];
            try {
                $data['errors'] = $settingProvider->handleSettingsForm($form, $request, $translator);
                $msm = new MailerSettingsManager();
                $msm->handleMailerDsn($request);
            } catch (\Exception $e) {
                $data['errors'][] = ['class' => 'error', 'message' => $translator->trans('Your request failed due to a database error.')];
            }

            $form = $this->createForm(EmailSettingsType::class, null, ['action' => $this->generateUrl('system_admin__third_party', ['tabName' => 'E-Mail']) ]);
            $manager->singlePanel($form->createView());
            $data['form'] = $manager->getFormFromContainer('formContent', 'single');

            return new JsonResponse($data,200);
        }

        $panel = new Panel('E-Mail');
        $container->addForm('E-Mail', $form->createView())->addPanel($panel);

        // Finally Finished
        $manager->addContainer($container)->buildContainers();

        return $this->render('modules/system_admin/third_party.html.twig');
    }
}