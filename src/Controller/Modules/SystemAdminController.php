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
use App\Entity\NotificationEvent;
use App\Entity\NotificationListener;
use App\Entity\Setting;
use App\Entity\StringReplacement;
use App\Form\Modules\SystemAdmin\DisplaySettingsType;
use App\Form\Modules\SystemAdmin\EmailSettingsType;
use App\Form\Modules\SystemAdmin\GoogleIntegationType;
use App\Form\Modules\SystemAdmin\LocalisationSettingsType;
use App\Form\Modules\SystemAdmin\MiscellaneousSettingsType;
use App\Form\Modules\SystemAdmin\NotificationEventType;
use App\Form\Modules\SystemAdmin\OrganisationSettingsType;
use App\Form\Modules\SystemAdmin\PaypalSettingsType;
use App\Form\Modules\SystemAdmin\SecuritySettingsType;
use App\Form\Modules\SystemAdmin\SMSSettingsType;
use App\Form\Modules\SystemAdmin\StringReplacementType;
use App\Form\Modules\SystemAdmin\SystemSettingsType;
use App\Manager\Entity\ImportReport;
use App\Manager\ExcelManager;
use App\Manager\SystemAdmin\GoogleSettingManager;
use App\Manager\SystemAdmin\ImportManager;
use App\Manager\SystemAdmin\LanguageManager;
use App\Manager\SystemAdmin\MailerSettingsManager;
use App\Manager\SystemAdmin\StringReplacementPagination;
use App\Manager\VersionManager;
use App\Provider\ProviderFactory;
use App\Util\GlobalHelper;
use App\Util\ReactFormHelper;
use App\Util\TranslationsHelper;
use App\Util\UserHelper;
use Doctrine\DBAL\Driver\PDOException;
use Doctrine\ORM\Query\QueryException;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Yaml\Yaml;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class SystemAdminController
 * @package App\Controller
 * @Route("/system_admin", name="system_admin__")
 */
class SystemAdminController extends AbstractController
{
    /**
     * systemSettings
     * @param Request $request
     * @Route("/system/{tabName}/settings/", name="system_settings")
     * @IsGranted("ROLE_ROUTE")
     */
    public function systemSettings(Request $request, ContainerManager $manager, TranslatorInterface $translator, string $tabName = 'System')
    {
        $settingProvider = ProviderFactory::create(Setting::class);
        $container = new Container();
        // System Settings
        $form = $this->createForm(SystemSettingsType::class, null, ['action' => $this->generateUrl('system_admin__system_settings', ['tabName' => 'System'])]);

        if ($tabName === 'System' && $request->getContentType() === 'json') {
            $data = [];
            try {
                $data['errors'] = $settingProvider->handleSettingsForm($form, $request, $translator);
            } catch (\Exception $e) {
                $data['errors'][] = ['class' => 'error', 'message' => $translator->trans('Your request failed due to a database error.')];
            }

            $manager->singlePanel($form->createView());
            $data['form'] = $manager->getFormFromContainer('formContent', 'single');

            return new JsonResponse($data, 200);
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

            return new JsonResponse($data, 200);
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

            return new JsonResponse($data, 200);
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

            return new JsonResponse($data, 200);
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

            return new JsonResponse($data, 200);
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
     * @Route("/third/{tabName}/party/", name="third_party")
     * @IsGranted("ROLE_ROUTE"))
     */
    public function thirdParty(Request $request, ContainerManager $manager, TranslatorInterface $translator, string $tabName = 'Google')
    {
        $settingProvider = ProviderFactory::create(Setting::class);
        $container = new Container();
        $container->setTarget('formContent')->setSelectedPanel($tabName)->setApplication('ThirdParty');

        // Google
        $form = $this->createForm(GoogleIntegationType::class, null, ['action' => $this->generateUrl('system_admin__third_party', ['tabName' => 'Google'])]);

        if ($tabName === 'Google' && $request->getContentType() === 'json') {
            $data = [];
            try {
                $data['errors'] = $settingProvider->handleSettingsForm($form, $request, $translator);
                $gm = new GoogleSettingManager();
                $data['errors'][] = $gm->handleGoogleSecretsFile($form, $request, $translator);
            } catch (\Exception $e) {
                $data['errors'][] = ['class' => 'error', 'message' => $translator->trans('Your request failed due to a database error.') . ' ' . $e->getMessage()];
            }

            $form = $this->createForm(GoogleIntegationType::class, null, ['action' => $this->generateUrl('system_admin__third_party', ['tabName' => 'Google'])]);
            $manager->singlePanel($form->createView());
            $data['form'] = $manager->getFormFromContainer('formContent', 'single');

            return new JsonResponse($data, 200);
        }

        $panel = new Panel('Google');
        $container->addForm('Google', $form->createView())->addPanel($panel);

        // PayPal
        $form = $this->createForm(PaypalSettingsType::class, null, ['action' => $this->generateUrl('system_admin__third_party', ['tabName' => 'PayPal'])]);

        if ($tabName === 'PayPal' && $request->getContentType() === 'json') {
            $data = [];
            try {
                $data['errors'] = $settingProvider->handleSettingsForm($form, $request, $translator);
            } catch (\Exception $e) {
                $data['errors'][] = ['class' => 'error', 'message' => $translator->trans('Your request failed due to a database error.')];
            }

            $form = $this->createForm(PaypalSettingsType::class, null, ['action' => $this->generateUrl('system_admin__third_party', ['tabName' => 'PayPal'])]);
            $manager->singlePanel($form->createView());
            $data['form'] = $manager->getFormFromContainer('formContent', 'single');

            return new JsonResponse($data, 200);
        }

        $panel = new Panel('PayPal');
        $container->addForm('PayPal', $form->createView())->addPanel($panel);

        // SMS
        $form = $this->createForm(SMSSettingsType::class, null, ['action' => $this->generateUrl('system_admin__third_party', ['tabName' => 'SMS'])]);

        if ($tabName === 'SMS' && $request->getContentType() === 'json') {
            $data = [];
            try {
                $data['errors'] = $settingProvider->handleSettingsForm($form, $request, $translator);
            } catch (\Exception $e) {
                $data['errors'][] = ['class' => 'error', 'message' => $translator->trans('Your request failed due to a database error.')];
            }

            $form = $this->createForm(SMSSettingsType::class, null, ['action' => $this->generateUrl('system_admin__third_party', ['tabName' => 'SMS'])]);
            $manager->singlePanel($form->createView());
            $data['form'] = $manager->getFormFromContainer('formContent', 'single');

            return new JsonResponse($data, 200);
        }

        $panel = new Panel('SMS');
        $container->addForm('SMS', $form->createView())->addPanel($panel);

        // E-Mail
        $form = $this->createForm(EmailSettingsType::class, null, ['action' => $this->generateUrl('system_admin__third_party', ['tabName' => 'E-Mail'])]);

        if ($tabName === 'E-Mail' && $request->getContentType() === 'json') {
            $data = [];
            try {
                $data['errors'] = $settingProvider->handleSettingsForm($form, $request, $translator);
                $msm = new MailerSettingsManager();
                $msm->handleMailerDsn($request);
            } catch (\Exception $e) {
                $data['errors'][] = ['class' => 'error', 'message' => $translator->trans('Your request failed due to a database error.')];
            }

            $form = $this->createForm(EmailSettingsType::class, null, ['action' => $this->generateUrl('system_admin__third_party', ['tabName' => 'E-Mail'])]);
            $manager->singlePanel($form->createView());
            $data['form'] = $manager->getFormFromContainer('formContent', 'single');

            return new JsonResponse($data, 200);
        }

        $panel = new Panel('E-Mail');
        $container->addForm('E-Mail', $form->createView())->addPanel($panel);

        // Finally Finished
        $manager->addContainer($container)->buildContainers();

        return $this->render('modules/system_admin/third_party.html.twig');
    }

    /**
     * check
     * @param VersionManager $manager
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/check/", name="check")
     * @IsGranted("ROLE_ROUTE")
     */
    public function check(VersionManager $manager)
    {

        return $this->render('modules/system_admin/check.html.twig',
            [
                'manager' => $manager->setEm($this->getDoctrine()->getManager()),
            ]
        );
    }

    /**
     * systemSettings
     * @param Request $request
     * @Route("/display/settings/", name="display_settings")
     * @IsGranted("ROLE_ROUTE")
     */
    public function displaySettings(Request $request, ContainerManager $manager, TranslatorInterface $translator)
    {
        $settingProvider = ProviderFactory::create(Setting::class);

        // System Settings
        $form = $this->createForm(DisplaySettingsType::class, null, ['action' => $this->generateUrl('system_admin__display_settings')]);

        if ($request->getContentType() === 'json') {
            $data = [];
            try {
                $data['errors'] = $settingProvider->handleSettingsForm($form, $request, $translator);
            } catch (\Exception $e) {
                $data['errors'][] = ['class' => 'error', 'message' => $translator->trans('Your request failed due to a database error.')];
            }

            $manager->singlePanel($form->createView());
            $data['form'] = $manager->getFormFromContainer('formContent', 'single');

            return new JsonResponse($data, 200);
        }

        $manager->singlePanel($form->createView());

        return $this->render('modules/system_admin/display_settings.html.twig');
    }

    /**
     * languageInstall
     * @param Request $request
     * @return RedirectResponse
     * @Route("/language/manage/", name="language_manage")
     * @IsGranted("ROLE_ROUTE")
     */
    public function languageManage(Request $request, LanguageManager $manager)
    {
        $langsInstalled = ProviderFactory::getRepository(I18n::class)->findBy(['installed' => 'Y'], ['code' => "ASC"]);
        $langsNotInstalled = ProviderFactory::getRepository(I18n::class)->findBy(['installed' => 'N'], ['code' => 'ASC']);

        return $this->render('modules/system_admin/language_manage.html.twig', [
            'installed' => $langsInstalled,
            'notInstalled' => $langsNotInstalled,
            'manager' => $manager,
            'translationPath' => realPath(__DIR__ . '/../../../translations'),
            'gVersion' => $this->getParameter('gibbon_version'),
        ]);
    }

    /**
     * languageSetDefault
     * @param I18n $i18n
     * @Route("/language/{i18n}/default/", name="language_default")
     * @Security("is_granted('ROLE_ROUTE', ['system_admin__language_manage'])")
     */
    public function languageSetDefault(I18n $i18n, SessionInterface $session)
    {
        $provider = ProviderFactory::create(I18n::class);
        $was = $provider->getRepository()->findOneBySystemDefault('Y');
        $was->setSystemDefault('N');
        $i18n->setSystemDefault('Y');
        $em = $this->getDoctrine()->getManager();
        $em->persist($was);
        $em->persist($i18n);
        $em->flush();
        $config = Yaml::parse(file_get_contents(__DIR__ . '/../../../config/packages/kookaburra.yaml'));
        $config['parameters']['locale'] = $i18n->getCode();
        file_put_contents(__DIR__ . '/../../../config/packages/kookaburra.yaml', Yaml::dump($config, 8));
        $this->addFlash('success', 'Your request was completed successfully.');
        $session->set('i18n', $i18n->toArray());
        return $this->redirectToRoute('system_admin__language_manage');
    }

    /**
     * languageInstall
     * @param Request $request
     * @return RedirectResponse
     * @Route("/language/{i18n}/install/", name="language_install")
     * @Security("is_granted('ROLE_ROUTE', ['system_admin__language_manage'])")
     */
    public function languageInstall(I18n $i18n, Request $request, LanguageManager $manager)
    {
        $installed = $manager->i18nFileInstall($i18n);

        if ($installed) {
            $i18n->setInstalled('Y');
            $i18n->setVersion($this->getParameter('gibbon_version'));
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

        if (!$installed) {
            $this->addFlash('error', 'The file transfer was not completed successfully.  Please try again.');
            return $this->redirectToRoute('system_admin__language_manage');
        }
        if (!$updated) {
            $this->addFlash('warning', 'Your request was successful, but some data was not properly saved.');
            return $this->redirectToRoute('system_admin__language_manage');
        }
        $this->addFlash('success', 'Your request was completed successfully.');
        return $this->redirectToRoute('system_admin__language_manage');
    }

    /**
     * notificationSettings
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/notification/settings/", name="notification_settings")
     * @IsGranted("ROLE_ROUTE")
     */
    public function notificationSettings()
    {
        $notificationProvider = ProviderFactory::create(NotificationEvent::class);

        return $this->render('modules/system_admin/notification_settings.html.twig',
            [
                'events' => $notificationProvider->selectAllNotificationEvents(),
            ]
        );
    }

    /**
     * notificationEventEdit
     * @param Request $request
     * @param NotificationEvent $event
     * @Route("/notification/{event}/edit/", name="notification_edit")
     * @IsGranted("ROLE_ROUTE")
     */
    public function notificationEventEdit(Request $request, NotificationEvent $event, ContainerManager $manager, ReactFormHelper $helper)
    {
        $form = $this->createForm(NotificationEventType::class, $event, ['action' => $this->generateUrl('system_admin__notification_edit', ['event' => $event->getId()]), 'listener_delete_route' => $this->generateUrl('system_admin__notification_listener_delete', ['listener' => '__id__', 'event' => '__event__'])]);

        if ($request->getContentType() === 'json') {
            $content = json_decode($request->getContent(), true);
            $form->submit($content);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                try {
                    $em->persist($event);
                    foreach ($event->getListeners() as $listener) {
                        $em->persist($listener);
                    }
                    $em->flush();
                    $em->refresh($event);
                    foreach ($event->getListeners() as $listener) {
                        $em->refresh($listener);
                    }
                    $form = $this->createForm(NotificationEventType::class, $event, ['action' => $this->generateUrl('system_admin__notification_edit', ['event' => $event->getId()]), 'listener_delete_route' => $this->generateUrl('system_admin__notification_listener_delete', ['listener' => '__id__', 'event' => '__event__'])]);
                    $data['errors'][] = ['class' => 'success', 'message' => TranslationsHelper::translate('Your request was completed successfully.')];
                } catch (PDOException $e) {
                    $data['errors'][] = ['class' => 'error', 'message' => TranslationsHelper::translate('Your request failed due to a database error.')];
                } catch (\PDOException $e) {
                    $data['errors'][] = ['class' => 'error', 'message' => TranslationsHelper::translate('Your request failed due to a database error.')];
                }
            } else {
                $data['errors'][] = ['class' => 'error', 'message' => TranslationsHelper::translate('Your request failed because your inputs were invalid.')];
            }

            $manager->singlePanel($form->createView());
            $data['form'] = $manager->getFormFromContainer('formContent', 'single');

            return new JsonResponse($data, 200);

        }

        $manager->singlePanel($form->createView(), 'NotificationEvent');

        return $this->render('/modules/system_admin/notification_edit.html.twig');
    }

    /**
     * notificationListenerDelete
     * @param NotificationEvent $event
     * @param NotificationListener $listener
     * @param ContainerManager $manager
     * @param ReactFormHelper $helper
     * @Route("/notification/{event}/listener/{listener}/delete/", name="notification_listener_delete")
     * @Security("is_granted('ROLE_ROUTE', ['system_admin__notification_edit'])");
     * @return JsonResponse
     */
    public function notificationListenerDelete(NotificationEvent $event, NotificationListener $listener, ContainerManager $manager, ReactFormHelper $helper)
    {
        $data = [];
        $data['errors'] = [];
        $data['form'] = [];
        $em = $this->getDoctrine()->getManager();
        if (!$event->getListeners()->contains($listener)) {
            $data['errors'][] = ['class' => 'error', 'message' => TranslationsHelper::translate('Your request failed because your inputs were invalid.', [], 'messages')];
            $data['status'] = 'error';
            return JsonResponse::create($data, 200);
        }

        try {
            $em->remove($listener);
            $em->flush();
        } catch (PDOException $e) {
            $data['errors'][] = ['class' => 'error', 'message' => TranslationsHelper::translate('Your request failed due to a database error.', [], 'messages')];
            $data['status'] = 'error';
            return JsonResponse::create($data, 200);
        }
        $em->refresh($event);
        $form = $this->createForm(NotificationEventType::class, $event, ['action' => $this->generateUrl('system_admin__notification_edit', ['event' => $event->getId()]), 'listener_delete_route' => $this->generateUrl('system_admin__notification_listener_delete', ['listener' => '__id__', 'event' => '__event__'])]);

        $manager->singlePanel($form->createView(), 'NotificationEvent');
        $data['form'] = $manager->getFormFromContainer('formContent', 'single');
        if ($data['errors'] === []) {
            $data['errors'][] = ['class' => 'success', 'message' => TranslationsHelper::translate('Your request was completed successfully.', [], 'messages')];
            $data['status'] = 'success';
        }

        //JSON Response required.
        return JsonResponse::create($data, 200);
    }

    /**
     * stringReplacementEdit
     * @param Request $request
     * @param ContainerManager $manager
     * @param string|null $stringReplacement
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/string/replacement/{stringReplacement}/edit/", name="string_replacement_edit")
     * @IsGranted("ROLE_ROUTE")
     */
    public function stringReplacementEdit(Request $request, ContainerManager $manager, ?string $stringReplacement = 'Add')
    {
        $stringReplacement = $stringReplacement !== 'Add' ? ProviderFactory::getRepository(StringReplacement::class)->find($stringReplacement) : new StringReplacement();

        $form = $this->createForm(StringReplacementType::class, $stringReplacement, ['action' => $this->generateUrl('system_admin__string_replacement_edit', ['stringReplacement' => $stringReplacement->getId() ?: 'Add'])]);

        if ($request->getContentType() === 'json') {
            $content = json_decode($request->getContent(), true);

            $data = [];
            $form->submit($content);
            if ($form->isValid()) {

                try {
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($stringReplacement);
                    $em->flush();
                    $data['errors'][] = ['class' => 'success', 'message' => TranslationsHelper::translate('Your request was completed successfully.', [], 'messages')];
                    $data['status'] = 'success';
                    $form = $this->createForm(StringReplacementType::class, $stringReplacement, ['action' => $this->generateUrl('system_admin__string_replacement_edit', ['stringReplacement' => $stringReplacement->getId() ?: 'Add'])]);
                } catch (PDOException $e) {
                    $data['errors'][] = ['class' => 'error', 'message' => TranslationsHelper::translate('Your request failed because your inputs were invalid.', [], 'messages')];
                    $data['status'] = 'error';
                }
            } else {
                $data['errors'][] = ['class' => 'error', 'message' => TranslationsHelper::translate('Your request failed due to a database error.', [], 'messages')];
                $data['status'] = 'error';
            }

            $manager->singlePanel($form->createView());
            $data['form'] = $manager->getFormFromContainer('formContent', 'single');

            return JsonResponse::create($data, 200);
        }

        $manager->singlePanel($form->createView());

        return $this->render('modules/system_admin/string_replacement_edit.html.twig');
    }

    /**
     * stringReplacementManage
     * @param Request $request
     * @param ContainerManager $manager
     * @param string|null $stringReplacement
     * @Route("/string/replacement/manage/", name="string_replacement_manage")
     * @IsGranted("ROLE_ROUTE")
     */
    public function stringReplacementManage(Request $request, ContainerManager $manager, StringReplacementPagination $pagination)
    {
        $content = [];
        $provider = ProviderFactory::create(StringReplacement::class);
        $content = $provider->getPaginationResults($request->query->get('search'));
        $pagination->setContent($content)
            ->setPaginationScript();
        return $this->render('modules/system_admin/string_replacement_manage.html.twig',
            [
                'content' => $content,
                'search' => $request->query->get('search') ?: '',
            ]
        );
    }

    /**
     * stringReplacementDelete
     * @param StringReplacement $stringReplacement
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/string/replacement/{stringReplacement}/delete/", name="string_replacement_delete")
     * @IsGranted("ROLE_ROUTE")
     */
    public function stringReplacementDelete(StringReplacement $stringReplacement)
    {
        try {
            $em =$this->getDoctrine()->getManager();
            $em->remove($stringReplacement);
            $em->flush();
            $this->addFlash('success', 'Your request was completed successfully.');
        } catch (PDOException $e) {
            $this->addFlash('error', 'Your request failed due to a database error.');
        }

        return $this->forward(SystemAdminController::class . '::stringReplacementManage');
    }

    /**
     * manageImport
     * @Route("/import/manage/", name="import_manage")
     * @IsGranted("ROLE_ROUTE")
     */
    public function manageImport(ImportManager $manager)
    {
        $manager->loadImportReportList();

        return $this->render('modules/system_admin/import_manage.html.twig',
            [
                'manager' => $manager,
            ]
        );
    }

    /**
     * exportRun
     * @param ImportManager $manager
     * @Route("/export/{report}/{data}/run/{all}", name="export_run")
     * @IsGranted("ROLE_ROUTE")
     */
    public function exportRun(string $report, ImportManager $manager, ExcelManager $excel, Request $request, bool $data = false, bool $all = false)
    {
        $manager->setDataExport($data || true);
        $manager->setDataExportAll($all);
        $session = $request->getSession();

        $report = $manager->getImportReport($report);
        if (!$report instanceof ImportReport)
            return $this->render('components/error.html.twig',
                [
                    'error' => 'Your request failed because your inputs were invalid.',
                ]
            );

        if (!$report->isImportAccessible())
            return $this->render('components/error.html.twig',
                [
                    'error' => 'Your request failed because you do not have access to this action.',
                ]
            );


        //Create border styles
        $style_head_fill= array(
            'fill' => array('fillType' => Fill::FILL_SOLID, 'startColor' => array('rgb' => 'eeeeee'), 'endColor' => array('rgb' => 'eeeeee')),
            'borders' => array('top' => array('borderStyle' => Border::BORDER_THIN, 'color' => array('rgb' => '444444'), ), 'bottom' => array('borderStyle' => Border::BORDER_THIN, 'color' => array('rgb' => '444444'), )),
        );

        // Set document properties
        $excel->getProperties()->setCreator(UserHelper::getCurrentUser()->formatName())
            ->setLastModifiedBy(UserHelper::getCurrentUser()->formatName())
            ->setTitle($report->getDetail('name'))
        ;

        $excel->setActiveSheetIndex(0);

        if ($manager->isDataExport()) {
            $count = 0;
            $rowData = [];
            $queryFields = [];
            $columnFields = $report->getAllFields();

            $columnFields = array_filter($columnFields, function ($fieldName) use ($report) {
                return !$report->isFieldHidden($fieldName);
            });

            // Create the header row
            foreach ($columnFields as $fieldName) {
                $excel->getActiveSheet()->setCellValue(GlobalHelper::num2alpha($count) . '1', $report->getField($fieldName, 'name', $fieldName));
                $excel->getActiveSheet()->getStyle(GlobalHelper::num2alpha($count) . '1')->applyFromArray($style_head_fill);

                // Dont auto-size giant text fields
                if ($report->getField($fieldName, 'kind') == 'text') {
                    $excel->getActiveSheet()->getColumnDimension(GlobalHelper::num2alpha($count))->setWidth(25);
                } else {
                    $excel->getActiveSheet()->getColumnDimension(GlobalHelper::num2alpha($count))->setAutoSize(true);
                }

                // Add notes to column headings
                $info = ($report->isFieldRequired($fieldName)) ? "* required\n" : '';
                $info .= $report->readableFieldType($fieldName) . "\n";
                $info .= $report->getField($fieldName, 'desc', '');
                $info = strip_tags($info);

                if (!empty($info)) {
                    $excel->getActiveSheet()->getComment(GlobalHelper::num2alpha($count) . '1')->getText()->createTextRun($info);
                }

                $count++;
            }

            $data = [];;
            $tableName = ucfirst($report->getDetail('table'));
            $query = $this->getDoctrine()->getManager()->createQueryBuilder();
            $query->from('\App\Entity\\' . $tableName, $report->getJoinAlias($tableName));

            foreach ($report->getJoin() as $fieldName => $join) {
                $type = $join['type'];
                $query->$type($report->getJoinAlias($join['table']) . '.' . $join['reference'], $join['alias']);
            }

            $select = [];
            foreach ($report->getFields() as $name=>$field) {
                $w = '';
                if (is_array($field['select'])) {
                    $w .= "CONCAT(";
                    foreach ($field['select'] as $name)
                        $w .= $name . ", ' ',";
                    $w = rtrim($w, "', ") . ")'";
                } elseif (!isset($field['select'])) {
                    $w = "''";
                } else {
                    $w .= $field['select'];
                }

                $w .= ' AS ' . $name;
                $select[] = $w;
            }

            $query->select($select);

            if (!$manager->isDataExportAll() && !in_array($tableName, ['SchoolYear', 'SchoolYearSpecialDay']))
            {
                // Optionally limit all exports to the current school year by default, to avoid massive files
                $schoolYear = $report->getTablesUsed();

                if (in_array('SchoolYear', $report->getTablesUsed()) && !$report->isFieldReadOnly('SchoolYear')) {
                    $data['schoolYear'] = $session->get('schoolYearCurrent')->getId();
                    $query->where($report->getJoinAlias('SchoolYear') . '.id = :schoolYear');
                }
            }

            if (null !== $report->getPrimaryKey())
                $query->setParameters($data)->orderBy($report->getJoinAlias($tableName) . '.' . $report->getPrimaryKey(), 'ASC');

            try {
                $result = $query->getQuery()->getResult();
            } catch (QueryException $e) {
                dd($tableName, $report, $query, $e->getMessage());
            }

            // Continue if there's data
            if (count($result) > 0) {

                $rowCount = 2;
                foreach ($result as $row) {

                    $i = 0;
                    foreach ($row as $name=>$value) {
                        switch ($report->getFieldFilter($name)) {
                            case 'date':
                                $excel->getActiveSheet()->setCellValue(GlobalHelper::num2alpha($i++) . $rowCount, null === $value ? '' : $value->format('Y-m-d'));
                                break;
                            case 'time':
                                $excel->getActiveSheet()->setCellValue(GlobalHelper::num2alpha($i++) . $rowCount, null === $value ? '' : $value->format('H:i:s'));
                                break;
                            case 'yesno':
                                $excel->getActiveSheet()->setCellValue(GlobalHelper::num2alpha($i++) . $rowCount, strtolower($value) === 'y' ? 'Yes' : 'No');
                                break;
                            default:
                                $excel->getActiveSheet()->setCellValue(GlobalHelper::num2alpha($i++) . $rowCount, (string)$value);
                        }
                    }
                    $rowCount++;
                }
            }
        }

        $filename = ($manager->isDataExport()) ? 'DataExport'.'-'.$report->getDetail('type') : 'DataStructure'.'-'.$report->getDetail('type');

        $excel->setFileName($filename);

        // FINALIZE THE DOCUMENT SO IT IS READY FOR DOWNLOAD
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $excel->setActiveSheetIndex(0);

        $excel->exportWorksheet();
    }
}