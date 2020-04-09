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
 * Time: 13:54
 */

namespace App\Manager;

use App\Entity\CourseClass;
use App\Entity\CourseClassPerson;
use App\Util\TranslationsHelper;
use Doctrine\ORM\EntityManagerInterface;
use Kookaburra\Departments\Entity\Department;
use Kookaburra\Departments\Entity\DepartmentStaff;
use Kookaburra\SystemAdmin\Manager\UpgradeManager;
use Kookaburra\UserAdmin\Entity\Person;
use Kookaburra\SystemAdmin\Entity\Role;
use Kookaburra\SystemAdmin\Entity\Setting;
use App\Entity\Staff;
use App\Provider\ProviderFactory;
use Kookaburra\UserAdmin\Manager\SecurityUser;
use App\Util\GlobalHelper;
use Kookaburra\UserAdmin\Util\SecurityHelper;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\UrlHelper;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Security\Core\Encoder\NativePasswordEncoder;
use Symfony\Component\Yaml\Yaml;
use Twig\Environment;

/**
 * Class InstallationManager
 * @package App\Manager
 */
class InstallationManager
{
    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var UrlHelper
     */
    private $urlHelper;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var ParameterBagInterface
     */
    private $bag;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var UpgradeManager
     */
    private $manager;

    /**
     * InstallationManager constructor.
     * @param Environment $twig
     * @param UrlHelper $urlHelper
     * @param LoggerInterface $logger
     * @param ParameterBagInterface $bag
     * @param EntityManagerInterface $em
     * @param UpgradeManager $manager
     */
    public function __construct(Environment $twig, UrlHelper $urlHelper, LoggerInterface $logger, ParameterBagInterface $bag, EntityManagerInterface $em, UpgradeManager $manager)
    {
        $this->twig = $twig;
        $this->urlHelper = $urlHelper;
        $this->logger = $logger;
        $this->bag = $bag;
        $this->em = $em;
        $this->manager = $manager;
    }

    /**
     * check
     * @param array $systemRequirements
     * @param FormInterface $form
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function check(array $systemRequirements)
    {
        $configFile = __DIR__ . '/../../config/packages/kookaburra.yaml';
        if (false === realpath($configFile) && false !== realpath($configFile.'.dist'))
        {
            if (false === copy($configFile.'.dist', $configFile)) {
                return new Response($this->twig->render('legacy/error.html.twig',
                    [
                        'extendedError' => 'Unable to copy the kookaburra.yaml file from the distribution file in /config/packages directory.',
                        'extendedParams' => [],
                        'manager' => $this,
                    ]
                ));
            } else {
                $config = $this->readKookaburraYaml();
                $config['parameters']['absoluteURL'] = str_replace('/install/installation/check/', '', $this->urlHelper->getAbsoluteUrl('/install/installation/check/'));
                $config['parameters']['guid'] = str_replace(['{','-','}'], '', com_create_guid());
                $this->writeKookaburraYaml($config);
                $this->getLogger()->notice(TranslationsHelper::translate('The config file has been created.'));
            }
        }

        $version = Yaml::parse(file_get_contents(__DIR__.'/../../config/packages/version.yaml'));
        $version = $version['parameters'];

        $ready = true;

        $systemDisplay = [];
        $systemDisplay['System Requirements'] = [];
        $systemDisplay['System Requirements']['PHP Version'] = [];
        $systemDisplay['System Requirements']['PHP Version']['name'] = 'PHP Version';
        $systemDisplay['System Requirements']['PHP Version']['comment'] = 'Kookaburra {version} requires PHP Version {php_version} or higher';
        $systemDisplay['System Requirements']['PHP Version']['comment_params'] = ['{version}' => $version['version'], '{php_version}' => $systemRequirements['php']];
        $systemDisplay['System Requirements']['PHP Version']['detail'] = PHP_VERSION;
        $systemDisplay['System Requirements']['PHP Version']['result'] = version_compare(PHP_VERSION, $systemRequirements['php'], '>=');
        $ready &= $systemDisplay['System Requirements']['PHP Version']['result'];

        $systemDisplay['System Requirements']['MySQL PDO Support'] = [];
        $systemDisplay['System Requirements']['MySQL PDO Support']['name'] = 'MySQL PDO Support';
        $systemDisplay['System Requirements']['MySQL PDO Support']['comment'] = '';
        $systemDisplay['System Requirements']['MySQL PDO Support']['detail'] = extension_loaded('pdo_mysql') ? 'Installed' : 'Not Installed';
        $systemDisplay['System Requirements']['MySQL PDO Support']['result'] = extension_loaded('pdo_mysql');
        $ready &= $systemDisplay['System Requirements']['MySQL PDO Support']['result'];

        $apacheModules = apache_get_modules();
        $systemDisplay['System Requirements']['mod_rewrite'] = [];
        $systemDisplay['System Requirements']['mod_rewrite']['name'] = 'Apache Module {name}';
        $systemDisplay['System Requirements']['mod_rewrite']['comment'] = '';
        $systemDisplay['System Requirements']['mod_rewrite']['detail'] = in_array('mod_rewrite', $apacheModules) ? 'Enabled' : 'N/A';
        $systemDisplay['System Requirements']['mod_rewrite']['result'] = in_array('mod_rewrite', $apacheModules);
        $ready &= $systemDisplay['System Requirements']['mod_rewrite']['result'];

        foreach($systemRequirements['extensions'] as $extension){
            $installed = extension_loaded($extension);
            $systemDisplay['System Requirements'][$extension] = [];
            $systemDisplay['System Requirements'][$extension]['name'] = 'PHP Extension {name}';
            $systemDisplay['System Requirements'][$extension]['comment'] = '';
            $systemDisplay['System Requirements'][$extension]['detail'] = $installed ? 'Installed' : 'Not Installed';
            $systemDisplay['System Requirements'][$extension]['result'] = $installed;
            $ready &= $systemDisplay['System Requirements'][$extension]['result'];
        }

        $message['class'] = 'success';
        $message['text'] = 'The directory containing the configuration files is writable, so the installation may proceed.';

        if (file_exists(__DIR__.'/../../config/packages/kookaburra.yaml') && !is_writable(__DIR__.'/../../config/packages/kookaburra.yaml')) {
            $message['class'] = 'error';
            $message['text'] = 'The directory containing the configuration files is not currently writable, or kookaburra.yaml is not writable, so the installer cannot proceed.';
            $this->getLogger()->error(TranslationsHelper::translate($message['text'] ));
        } else { //No config, so continue installer
            if (!is_writable(__DIR__.'/../../config/packages/')) { // Ensure that home directory is writable
                $message['class'] = 'error';
                $message['text'] = 'The directory containing the configuration files is not currently writable, or kookaburra.yaml is not writable, so the installer cannot proceed.';
                $this->getLogger()->error(TranslationsHelper::translate($message['text'] ));
            }
        }

        if (!$ready){
            $message['class'] = 'error';
            $message['text'] = 'One or more of the system requirements listed above is not configured correctly.';
            $this->getLogger()->error(TranslationsHelper::translate($message['text'] ));
        }

        if ($message['class'] === 'success')
            $this->getLogger()->notice(TranslationsHelper::translate($message['text'] ));

        return
            $this->twig->render('installation/check.html.twig',
                [
                    'systemRequirements' => $systemRequirements,
                    'systemDisplay' => $systemDisplay,
                    'ready' => $ready,
                    'message' => $message,
                ]
            )
        ;
    }

    /**
     * readKookaburraYaml
     * @return array
     */
    private function readKookaburraYaml(): array
    {
        return GlobalHelper::readKookaburraYaml();
    }

    /**
     * writeKookaburraYaml
     * @param array $config
     */
    private function writeKookaburraYaml(array $config)
    {
        GlobalHelper::writeKookaburraYaml($config);
        $this->getLogger()->notice(TranslationsHelper::translate('The config file was written.'));
    }

    /**
     * getLocale
     * @return string|null
     */
    public function getLocale(): ?string
    {
        $config = $this->readKookaburraYaml();
        return $config['parameters']['locale'];
    }

    /**
     * setLocale
     * @param string $locale
     */
    public function setLocale(string $locale)
    {
        $config = $this->readKookaburraYaml();
        $config['parameters']['locale'] = $locale;
        $this->getLogger()->notice(TranslationsHelper::translate('The locale was set to {locale}', ['{locale}' => $locale]), ['locale' => $locale]);
        $this->writeKookaburraYaml($config);
    }

    /**
     * setMySQLSettings
     * @param FormInterface $form
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function setMySQLSettings(FormInterface $form)
    {
        $setting = $form->getData();
        $config = $this->readKookaburraYaml();

        $config['parameters']['databaseServer']         = $setting->getHost();
        $config['parameters']['databaseName']           = $setting->getDbname();
        $config['parameters']['databasePort']           = $setting->getPort();
        $config['parameters']['databaseUsername']       = $setting->getUser();
        $config['parameters']['databasePassword']       = $setting->getPassword();
        $config['parameters']['databasePrefix']         = $setting->getPrefix();
        $config['parameters']['installation']['demo']   = $setting->isDemo(); ;

        $this->writeKookaburraYaml($config);

        $message['class'] = 'success';
        $message['text'] = 'The MySQL Database settings have been successfully tested and saved. You can now proceed to build the database.';

        $this->getLogger()->notice($message['text']);

        return new Response($this->twig->render('installation/mysql_settings.html.twig',
            [
                'form' => $form->createView(),
                'message' => $message,
                'sidebarOptions' => [
                    'proceed' => true,
                ],
            ]
        ));
    }

    /**
     * buildDatabase
     * @param KernelInterface $kernel
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function buildDatabase(KernelInterface $kernel, Request $request): Response
    {
        $this->manager->setLogger($this->getLogger());
        $this->manager->installation($kernel);
        $application = new Application($kernel);
        $application->setAutoExit(false);
        $this->getLogger()->notice(TranslationsHelper::translate('Module Installation commenced.'));

        $this->setInstallationStatus('system');
        $this->getLogger()->notice(TranslationsHelper::translate('The database build was completed.'));

        return new RedirectResponse($request->server->get('REQUEST_SCHEME') . '://' . $request->server->get('SERVER_NAME') . '/install/installation/system/');
    }

    /**
     * getPasswordPolicy
     * @return array
     */
    public function getPasswordPolicy(): array
    {
        return SecurityHelper::getPasswordPolicy();
    }

    /**
     * setAdministrator
     * @param FormInterface $form
     */
    public function setAdministrator(FormInterface $form)
    {
        $person = ProviderFactory::getRepository(Person::class)->loadUserByUsernameOrEmail($form->get('username')->getData()) ?: new Person();
        $person->setTitle($form->get('title')->getData());
        $person->setSurname($form->get('surname')->getData());
        $person->setFirstName($form->get('firstName')->getData());
        $person->setPreferredName($form->get('firstName')->getData());
        $person->setOfficialName($form->get('firstName')->getData().' '.$form->get('surname')->getData());
        $person->setusername($form->get('username')->getData());
        $encoder = new NativePasswordEncoder();

        $password = $encoder->encodePassword($form->get('password')->getData(), null);
        $person->setPassword($password);
        $person->setStatus('Full');
        $person->setCanLogin('Y');
        $person->setPrimaryRole(ProviderFactory::getRepository(Role::class)->findOneByName('Administrator'));
        $person->setEmail($form->get('email')->getData());
        $person->setViewCalendarSchool('Y');
        $person->setViewCalendarSpaceBooking('Y');
        $em = ProviderFactory::getEntityManager();
        $em->persist($person);
        $em->flush();

        if ($person->getId() > 1) {
            $sql = 'UPDATE `gibbonPerson` SET `id` = 1 WHERE `username` = :username';
            $statement = $em->getConnection()->prepare($sql);
            $statement->bindValue('username', $form->get('username')->getData());
            $statement->execute();
            $person = ProviderFactory::getRepository(Person::class)->find(1);
            $em->refresh($person);
        }

        $staff = new Staff();
        $staff->setType('Support')
            ->setJobTitle('System Administrator')
            ->setPerson($person);
        $em->persist($staff);
        $em->flush();
        new SecurityUser($person);

        if ($this->isDemo()) {
            //Add admin to Course CLass
            $ccp = new CourseClassPerson();
            $ccp->setPerson($person)->setRole('Teacher')->setCourseClass(ProviderFactory::getRepository(CourseClass::class)->find(2426))->setReportable('Y');
            $em->persist($ccp);

            $ccp = new CourseClassPerson();
            $ccp->setPerson($person)->setRole('Teacher')->setCourseClass(ProviderFactory::getRepository(CourseClass::class)->find(2425))->setReportable('Y');
            $em->persist($ccp);

            $ccp = new CourseClassPerson();
            $ccp->setPerson($person)->setRole('Teacher')->setCourseClass(ProviderFactory::getRepository(CourseClass::class)->find(2424))->setReportable('Y');
            $em->persist($ccp);

            $ccp = new CourseClassPerson();
            $ccp->setPerson($person)->setRole('Teacher')->setCourseClass(ProviderFactory::getRepository(CourseClass::class)->find(2548))->setReportable('Y');
            $em->persist($ccp);

            $ccp = new CourseClassPerson();
            $ccp->setPerson($person)->setRole('Teacher')->setCourseClass(ProviderFactory::getRepository(CourseClass::class)->find(2327))->setReportable('Y');
            $em->persist($ccp);
            $em->flush();

            $entity = new DepartmentStaff();
            $entity->setPerson($person)->setRole('Teacher (Curriculum)')->setDepartment(ProviderFactory::getRepository(Department::class)->findOneBy(['nameShort' => 'TA']));
            $em->persist($entity);

            $entity = new DepartmentStaff();
            $entity->setPerson($person)->setRole('Teacher (Curriculum)')->setDepartment(ProviderFactory::getRepository(Department::class)->findOneBy(['nameShort' => 'HT']));
            $em->persist($entity);

            $em->flush();

        }
    }

    /**
     * setSystemSettings
     * @param FormInterface $form
     */
    public function setSystemSettings(FormInterface $form)
    {
        $settingProvider = ProviderFactory::create(Setting::class);

        $baseUrl = str_replace('http:','https:', $form->get('baseUrl')->getData());
        $settingProvider->setSettingByScope('System', 'absoluteURL', $baseUrl);
        $settingProvider->setSettingByScope('System', 'absolutePath', $form->get('basePath')->getData());
        $settingProvider->setSettingByScope('System', 'systemName', $form->get('systemName')->getData());
        $settingProvider->setSettingByScope('System', 'installType', $form->get('installType')->getData());
        $settingProvider->setSettingByScope('System', 'organisationName', $form->get('organisationName')->getData());
        $settingProvider->setSettingByScope('System', 'organisationNameShort', $form->get('organisationNameShort')->getData());
        $settingProvider->setSettingByScope('System', 'country', $form->get('country')->getData());
        $settingProvider->setSettingByScope('System', 'currency', $form->get('currency')->getData());
        $settingProvider->setSettingByScope('System', 'timezone', $form->get('timezone')->getData());
        $config = $this->readKookaburraYaml();
        $config['parameters']['absoluteURL'] = $baseUrl;
        $config['parameters']['timezone'] = $form->get('timezone')->getData();
        $config['parameters']['system_name'] = $form->get('systemName')->getData();
        $config['parameters']['organisation_name'] = $form->get('organisationName')->getData();
        unset($config['parameters']['installation']);
        $this->writeKookaburraYaml($config);
    }

    /**
     * setInstallationStatus
     * @param string $status
     */
    public function setInstallationStatus(string $status)
    {
        $config = $this->readKookaburraYaml();
        $config['parameters']['installation']['status'] = $status;
        if ($status === 'complete') {
            $config['parameters']['installed'] = true;
            unset($config['parameters']['installation']);
        }
        $this->writeKookaburraYaml($config);
        $this->getLogger()->notice(TranslationsHelper::translate('The installation status was set to {status}.', ['{status}' => $status], 'messages'), ['status' => $status]);
    }

    /**
     * buildDatabase
     * @param KernelInterface $kernel
     * @throws \Exception
     */
    public function moduleInstall(KernelInterface $kernel)
    {
        $application = new Application($kernel);
        $application->setAutoExit(true);

        $input = new ArrayInput([
            'command' => 'kookaburra:module:install',
            // (optional) define the value of command arguments
            // 'fooArgument' => 'barValue',
            // (optional) pass options to the command
            '--quiet' => '--quiet',
            '--no-interaction' => '--no-interaction',
        ]);

        // You can use NullOutput() if you don't need the output
        $output = new NullOutput();
        $application->run($input, $output);

        // return the output, don't use if you used NullOutput()
        // $content = $output->fetch();
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }

    /**
     * @return ParameterBagInterface
     */
    public function getBag(): ParameterBagInterface
    {
        return $this->bag;
    }

    /**
     * @var boolean
     */
    private $demo;

    /**
     * isDemo
     * @return bool
     */
    public function isDemo(): bool
    {
        if (null !== $this->demo)
            return $this->demo;
        if ($this->getBag()->has('installation')) {
            $installation = $this->getBag()->get('installation');
            if (isset($installation['demo']))
                $this->demo = $installation['demo'];
            else
                return false;
        } else
            return false;
        return $this->demo;
    }
}