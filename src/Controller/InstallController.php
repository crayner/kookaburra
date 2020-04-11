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

use App\Container\ContainerManager;
use App\Manager\Entity\Language;
use App\Manager\PageManager;
use App\Util\ErrorMessageHelper;
use Kookaburra\SystemAdmin\Entity\I18n;
use App\Form\Entity\MySQLSettings;
use App\Form\Installation\LanguageType;
use App\Form\Installation\MySQLType;
use App\Form\Installation\SystemType;
use App\Manager\InstallationManager;
use App\Provider\ProviderFactory;
use App\Util\TranslationsHelper;
use Doctrine\ORM\EntityManagerInterface;
use Kookaburra\SystemAdmin\Form\Entity\SystemSettings;
use Kookaburra\SystemAdmin\Manager\LanguageManager;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class InstallController
 * @package App\Controller
 * @Route("/install", name="install__")
 */
class InstallController extends AbstractController implements LoggerAwareInterface
{
    /**
     * installationCheck
     *
     * @param PageManager $pageManager
     * @param InstallationManager $manager
     * @param ValidatorInterface $validator
     * @param ContainerManager $containerManager
     * @param TranslationsHelper $helper
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     * @Route("/installation/check/", name="installation_check")
     */
    public function installationCheck(PageManager $pageManager, InstallationManager $manager, ValidatorInterface $validator, ContainerManager $containerManager, TranslationsHelper $helper)
    {
        if ($pageManager->isNotReadyForJSON()) return $pageManager->getBaseResponse();
        $request = $pageManager->getRequest();
        $i18n = new Language();

        $form = $this->createForm(LanguageType::class, $i18n, ['action' => $this->generateUrl('install__installation_check', [], UrlGeneratorInterface::ABSOLUTE_URL)]);

        if ($request->getContent() !== '') {
            $i18n = new Language();
            $content = json_decode($request->getContent(), true);

            $i18n->setCode($content['code']);
            $form = $this->createForm(LanguageType::class, $i18n, ['action' => $this->generateUrl('install__installation_check', [], UrlGeneratorInterface::ABSOLUTE_URL)]);

            $list = $validator->validate($content['code'], [
                new NotBlank(),
                new Choice(['choices' => I18n::getLanguages()]),
            ]);

            if ($list->count() === 0) {
                $manager->setLocale($form->get('code')->getData());
                $manager->setInstallationStatus('mysql');
                $data = ErrorMessageHelper::getSuccessMessage([], true);
                $data['status'] = 'redirect';
                $data['redirect'] = $this->generateUrl('install__installation_mysql', [], UrlGeneratorInterface::ABSOLUTE_URL);
                $fs = new Filesystem();
                $fs->remove(__DIR__. '/../../var/cache/*');
                return new JsonResponse($data);
            } else {
                $data = ErrorMessageHelper::getInvalidInputsMessage([],true);
                $containerManager->singlePanel($form->createView());
                $data['form'] = $containerManager->getFormFromContainer();
                return new JsonResponse($data);
            }
        }

        $containerManager->singlePanel($form->createView());

        return $pageManager->render(
            [
                'content' => $manager->check($this->getParameter('systemRequirements')),
                'containers' => $containerManager->getBuiltContainers(),
            ]
        );
    }

    /**
     * installationMySQLSettings
     * @param PageManager $pageManager
     * @param InstallationManager $manager
     * @param ContainerManager $containerManager
     * @param TranslationsHelper $helper
     * @param string $proceed
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     * @Route("/installation/mysql/{proceed}", name="installation_mysql")
     */
    public function installationMySQLSettings(PageManager $pageManager, InstallationManager $manager, ContainerManager $containerManager, TranslationsHelper $helper,  string $proceed = '0')
    {
        if ($pageManager->isNotReadyForJSON()) return $pageManager->getBaseResponse();
        $request = $pageManager->getRequest();

        $mysql = new MySQLSettings();
        $manager->readCurrentMySQLSettings($mysql);
        $data = null;

        $form = $this->createForm(MySQLType::class, $mysql, ['action' => $this->generateUrl('install__installation_mysql', ['proceed' => $proceed], UrlGeneratorInterface::ABSOLUTE_URL), 'proceed' => $proceed]);

        if ($request->getContent() !== '') {
            $content = json_decode($request->getContent(), true);
            $form->submit($content);

            if ($form->isValid()) {
                $manager->setInstallationStatus('build');
                $data = $manager->setMySQLSettings($form);
                $data['status'] = 'redirect';
                $data['redirect'] = $this->generateUrl('install__installation_mysql', ['proceed' => '1'], UrlGeneratorInterface::ABSOLUTE_URL);
                if ($proceed === '1' && key_exists('proceedFlag', $content) && $content['proceedFlag'] === 'Ready to Go') {
                    $data['redirect'] = $this->generateUrl('install__installation_build', [], UrlGeneratorInterface::ABSOLUTE_URL);
                    $data['status'] = 'newPage';
                }
            } else {
                $containerManager->singlePanel($form->createView());
                $data = ErrorMessageHelper::getInvalidInputsMessage([],true);
                $data['form'] = $containerManager->getFormFromContainer();
            }
            file_put_contents(__DIR__ . '/../../var/log/data.txt', json_encode($data));
            return new JsonResponse($data);
        }

        $containerManager->singlePanel($form->createView());

        return $pageManager->render(
            [
                'content' => $this->renderView('installation/mysql_settings.html.twig',
                    [
                        'message' => $data ? $data['errors'][0] : null,
                    ]
                ),
                'containers' => $containerManager->getBuiltContainers(),
            ]
        );
    }

    /**
     * installationBuild
     * @param InstallationManager $manager
     * @param KernelInterface $kernel
     * @param Request $request
     * @return Response
     * @throws \Exception
     * @Route("/installation/build/", name="installation_build")
     */
    public function installationBuild(InstallationManager $manager, KernelInterface $kernel, Request $request, EntityManagerInterface $em)
    {
        $manager->getLogger()->notice(TranslationsHelper::translate('The build of the database has commenced.'));
        return $manager->buildDatabase($kernel, $request);
    }

    /**
     * installationSystem
     * @param Request $request
     * @param InstallationManager $manager
     * @Route("/installation/system/", name="installation_system")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
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
     * @return Response
     * @throws \Exception
     * @Route("/installation/complete/", name="installation_complete")
     */
    public function installationComplete(InstallationManager $manager, LanguageManager $languageManager, KernelInterface $kernel)
    {
        $i18n = ProviderFactory::getRepository(I18n::class)->findOneByCode($manager->getLocale());
        $languageManager->i18nFileInstall($i18n);
        $manager->setInstallationStatus('complete');

        return $this->render('installation/complete.html.twig');
    }

    /**
     * installationComplete
     * @param InstallationManager $manager
     * @return Response
     * @Route("/update/", name="update")
     * @IsGranted("ROLE_SYSTEM_ADMIN")
     */
    public function update(){

    }

    /**
     * @var
     */
    private $logger;

    /**
     * @return mixed
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * setLogger
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
}