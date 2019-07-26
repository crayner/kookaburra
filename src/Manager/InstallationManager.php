<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 22/07/2019
 * Time: 13:54
 */

namespace App\Manager;

use App\Entity\Person;
use App\Entity\Role;
use App\Entity\Setting;
use App\Entity\Staff;
use App\Provider\ProviderFactory;
use App\Security\SecurityUser;
use App\Security\SHA256PasswordEncoder;
use App\Util\GlobalHelper;
use App\Util\SecurityHelper;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\UrlHelper;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Intl\Currencies;
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
     * InstallationManager constructor.
     * @param Environment $twig
     */
    public function __construct(Environment $twig, UrlHelper $urlHelper)
    {
        $this->twig = $twig;
        $this->urlHelper = $urlHelper;
    }

    /**
     * check
     * @param array $systmRequirements
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function check(array $systemRequirements, FormInterface $form)
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
                $config['parameters']['absoluteURL'] = str_replace('/installation/check/', '', $this->urlHelper->getAbsoluteUrl('/installation/check/'));
                $config['parameters']['guid'] = str_replace(['{','-','}'], '', com_create_guid());
                $this->writeKookaburraYaml($config);
            }
        }

        $ready = true;

        $systemDisplay = [];
        $systemDisplay['System Requirements'] = [];
        $systemDisplay['System Requirements']['PHP Version'] = [];
        $systemDisplay['System Requirements']['PHP Version']['name'] = 'PHP Version';
        $systemDisplay['System Requirements']['PHP Version']['comment'] = 'Kookaburra %{version} requires PHP Version %{php_version} or higher';
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
        $systemDisplay['System Requirements']['mod_rewrite']['name'] = 'Apache Module %{name}';
        $systemDisplay['System Requirements']['mod_rewrite']['comment'] = '';
        $systemDisplay['System Requirements']['mod_rewrite']['detail'] = in_array('mod_rewrite', $apacheModules) ? 'Enabled' : 'N/A';
        $systemDisplay['System Requirements']['mod_rewrite']['result'] = in_array('mod_rewrite', $apacheModules);
        $ready &= $systemDisplay['System Requirements']['mod_rewrite']['result'];

        foreach($systemRequirements['extensions'] as $extension){
            $installed = extension_loaded($extension);
            $systemDisplay['System Requirements'][$extension] = [];
            $systemDisplay['System Requirements'][$extension]['name'] = 'PHP Extension %{name}';
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
        } else { //No config, so continue installer
            if (!is_writable(__DIR__.'/../../config/packages/')) { // Ensure that home directory is writable
                $message['class'] = 'error';
                $message['text'] = 'The directory containing the configuration files is not currently writable, or kookaburra.yaml is not writable, so the installer cannot proceed.';
            }
        }

        if (!$ready){
            $message['class'] = 'error';
            $message['text'] = 'One or more of the system requirements listed above is not configured correctly.';
        }


        return new Response($this->twig->render('installation/ckeck.html.twig',
            [
                'systemRequirements' => $systemRequirements,
                'systemDisplay' => $systemDisplay,
                'ready' => $ready,
                'message' => $message,
                'form' => $form->createView(),
            ]
        ));
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
    }

    /**
     * setLocale
     * @param string $locale
     */
    public function setLocale(string $locale)
    {
        $config = $this->readKookaburraYaml();
        $config['parameters']['locale'] = $locale;
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
        $config['parameters']['installation']['demo']   = $setting->isDemo(); ;

        $this->writeKookaburraYaml($config);

        $message['class'] = 'success';
        $message['text'] = 'The MySQL Database settings have been successfully tested and saved. You can now proceed to build the database.';

        return new Response($this->twig->render('installation/mysql_settings.html.twig',
            [
                'form' => $form->createView(),
                'message' => $message,
                'proceed' => true,
            ]
        ));
    }

    /**
     * buildDatabase
     * @param KernelInterface $kernel
     * @return Response
     * @throws \Exception
     */
    public function buildDatabase(KernelInterface $kernel): Response
    {
        $application = new Application($kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput([
            'command' => 'doctrine:migrations:migrate',
            // (optional) define the value of command arguments
            // 'fooArgument' => 'barValue',
            // (optional) pass options to the command
            '--quiet' => '--quiet',
            '--no-interaction' => '--no-interaction',
        ]);

        // You can use NullOutput() if you don't need the output
        $output = new BufferedOutput();
        $application->run($input, $output);

        // return the output, don't use if you used NullOutput()
        $content = $output->fetch();

        if ('' !== $content)
            return new Response($content);// if you used NullOutput()

        $this->setInstallationStatus('system');
        return new RedirectResponse('/installation/system/');
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
        $person = ProviderFactory::getRepository(Person::class)->findOneByUsername($form->get('username')->getData()) ?: new Person();
        $person->setTitle($form->get('title')->getData());
        $person->setSurname($form->get('surname')->getData());
        $person->setFirstName($form->get('firstName')->getData());
        $person->setPreferredName($form->get('firstName')->getData());
        $person->setOfficialName($form->get('firstName')->getData().' '.$form->get('surname')->getData());
        $person->setusername($form->get('username')->getData());
        $encoder = new SHA256PasswordEncoder();
        $salt = SecurityUser::createSalt();

        $password = $encoder->encodePassword($form->get('password')->getData(), $salt);
        $person->setPasswordStrongSalt($salt);
        $person->setPasswordStrong($password);
        $person->setStatus('Full');
        $person->setCanLogin('Y');
        $person->setPrimaryRole(ProviderFactory::getRepository(Role::class)->find(1));
        $person->setEmail($form->get('email')->getData());
        $person->setViewCalendarSchool('Y');
        $person->setViewCalendarSpaceBooking('Y');

        $em = ProviderFactory::getEntityManager();
        $em->persist($person);
        $em->flush();

        if ($person->getId() > 1) {
            $sql = 'UPDATE `gibbonPerson` SET `gibbonPersonID` = 1 WHERE `username` = :username';
            $statement = $em->getConnection()->prepare($sql);
            $statement->bindValue('username', $form->get('username')->getData());
            $statement->execute();
            $person = ProviderFactory::getRepository(Person::class)->find(1);
        }
        $person->setStaff(ProviderFactory::getRepository(Staff::class)->find(1));
        $securityUser = new SecurityUser($person);
    }

    /**
     * setSystemSettings
     * @param FormInterface $form
     */
    public function setSystemSettings(FormInterface $form)
    {
        $settingProvider = ProviderFactory::create(Setting::class);

        $settingProvider->setSettingByScope('System', 'absoluteURL', $form->get('baseUrl')->getData());
        $settingProvider->setSettingByScope('System', 'absolutePath', $form->get('basePath')->getData());
        $settingProvider->setSettingByScope('System', 'systemName', $form->get('systemName')->getData());
        $settingProvider->setSettingByScope('System', 'installType', $form->get('installType')->getData());
        $settingProvider->setSettingByScope('System', 'organisationName', $form->get('organisationName')->getData());
        $settingProvider->setSettingByScope('System', 'organisationNameShort', $form->get('organisationNameShort')->getData());
        $settingProvider->setSettingByScope('System', 'country', $form->get('country')->getData());
        $settingProvider->setSettingByScope('System', 'currency', $form->get('currency')->getData().' '.Currencies::getSymbol($form->get('currency')->getData()));
        $settingProvider->setSettingByScope('System', 'timezone', $form->get('timezone')->getData());
        $config = $this->readKookaburraYaml();
        $config['parameters']['absoluteURL'] = $form->get('baseUrl')->getData();
        $config['parameters']['timezone'] = $form->get('timezone')->getData();
        unset( $config['parameters']['installation']);
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

    }
}