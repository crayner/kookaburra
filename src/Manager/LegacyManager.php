<?php
/**
 * Created by PhpStorm.
 *
 * bilby
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 25/06/2019
 * Time: 10:20
 */

namespace App\Manager;

use App\Entity\Hook;
use App\Entity\Module;
use App\Entity\Setting;
use App\Entity\StudentEnrolment;
use App\Provider\ProviderFactory;
use Gibbon\UI\Components\Header;
use Gibbon\UI\Components\Sidebar;
use Gibbon\UI\Dashboard\ParentDashboard;
use Gibbon\UI\Dashboard\StaffDashboard;
use Gibbon\UI\Dashboard\StudentDashboard;
use Gibbon\View\Page;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;


/**
 * Class LegacyManager
 * @package App\Manager
 */
class LegacyManager
{
    /**
     * @var ProviderFactory
     */
    private $providerFactory;

    /**
     * @var StaffDashboard
     */
    private $staffDashboard;

    /**
     * @var Header
     */
    private $header;

    /**
     * @var
     */
    private $sidebar;

    /**
     * LegacyManager constructor.
     * @param RequestStack $stack
     */
    public function __construct(ProviderFactory $providerFactory, StaffDashboard $staffDashboard, Header $header, Sidebar $sidebar)
    {
        $this->providerFactory = $providerFactory;
        $this->staffDashboard = $staffDashboard;
        $this->header = $header;
        $this->sidebar = $sidebar;
    }

    /**
     * execute
     * @param Request $request
     */
    public function execute(Request $request, Page $page)
    {
        $cwd = getcwd();
        chdir(realpath(__DIR__.'/../../Gibbon'));
        $session = $request->getSession();
        $gibbon = GibbonManager::getGibbon();
        $guid = GibbonManager::getGuid();
        $pdo = GibbonManager::getConnection();
        $connection2 = GibbonManager::getPDO();
        $settingProvider = $this->providerFactory->getProvider(Setting::class);

        $isLoggedIn = $session->has('username') && $session->has('gibbonRoleIDCurrent') ? true : false;

        /**
         * MODULE BREADCRUMBS
         */
        if ($isLoggedIn && $module = $page->getModule()) {
            $page->breadcrumbs->setBaseURL('index.php?q=/modules/'.$module->name.'/');
            $page->breadcrumbs->add(__($module->name), $module->entryURL);
        }

        /**
         * CACHE & INITIAL PAGE LOAD
         *
         * The 'pageLoads' value is used to run code when the user first logs in, and
         * also to reload cached content based on the $caching value in config.php
         *
         * TODO: When we implement routing, these can become part of the HTTP middleware.
         */
        $session->set('pageLoads', !$session->exists('pageLoads') ? 0 : $session->get('pageLoads', -1)+1);

        $cacheLoad = true;
        $caching = $gibbon->getConfig('caching');
        if (!empty($caching) && is_numeric($caching)) {
            $cacheLoad = $session->get('pageLoads') % intval($caching) == 0;
        }
        $cacheLoad = false;

        /**
         * SYSTEM SETTINGS
         *
         * Checks to see if system settings are set from database. If not, tries to
         * load them in. If this fails, something horrible has gone wrong ...
         *
         * TODO: Move this to the GibbonSession creation logic.
         * TODO: Handle the exit() case with a pre-defined error template.
         */
        if (!$session->has('systemSettingsSet')) {
            $settingProvider->getSystemSettings($session);

            if (!$session->has('systemSettingsSet')) {
                return GibbonManager::returnErrorResponse('System Settings are not set: The system cannot be displayed.');
            }
        }

        /**
         * USER REDIRECTS
         *
         * TODO: When we implement routing, these can become part of the HTTP middleware.
         */

        // Check for force password reset flag
        if ($session->has('passwordForceReset')) {
            if ($session->get('passwordForceReset') == 'Y' && $session->get('address') != 'preferences.php') {
                $URL = $session->get('absoluteURL').'/preferences/';
                $URL = $URL.'&forceReset=Y';
                return new RedirectResponse($URL);
            }
        }

        // Redirects after login
        if ($session->get('pageLoads') === 0 && !$session->has('address')) { // First page load, so proceed

            if ($session->has('username')) { // Are we logged in?
                $roleCategory = getRoleCategory($session->get('gibbonRoleIDCurrent'), $connection2);

                // Deal with attendance self-registration redirect
                // Are we a student?
                if ($roleCategory == 'Student') {
                    // Can we self register?
                    if (isActionAccessible($guid, $connection2, '/modules/Attendance/attendance_studentSelfRegister.php')) {
                        // Check to see if student is on site
                        $studentSelfRegistrationIPAddresses = $settingProvider->getSettingByScope(
                            'Attendance',
                            'studentSelfRegistrationIPAddresses'
                        );
                        $realIP = getIPAddress();
                        if ($studentSelfRegistrationIPAddresses != '' && !is_null($studentSelfRegistrationIPAddresses)) {
                            $inRange = false ;
                            foreach (explode(',', $studentSelfRegistrationIPAddresses) as $ipAddress) {
                                if (trim($ipAddress) == $realIP) {
                                    $inRange = true ;
                                }
                            }
                            if ($inRange) {
                                $currentDate = date('Y-m-d');
                                if (isSchoolOpen($guid, $currentDate, $connection2, true)) { // Is school open today
                                    // Check for existence of records today
                                    try {
                                        $data = array('gibbonPersonID' => $session->get('gibbonPersonID'), 'date' => $currentDate);
                                        $sql = "SELECT type FROM gibbonAttendanceLogPerson WHERE gibbonPersonID=:gibbonPersonID AND date=:date ORDER BY timestampTaken DESC";
                                        $result = $connection2->prepare($sql);
                                        $result->execute($data);
                                    } catch (\PDOException $e) {
                                        $page->addError($e->getMessage());
                                    }

                                    if ($result->rowCount() == 0) {
                                        // No registration yet
                                        // Redirect!
                                        $URL = $session->get('absoluteURL').
                                            '/index.php?q=/modules/Attendance'.
                                            '/attendance_studentSelfRegister.php'.
                                            '&redirect=true';
                                        $session->set('pageLoads', null);
                                        header("Location: {$URL}");
                                        exit;
                                    }
                                }
                            }
                        }
                    }
                }

                // Deal with Data Updater redirect (if required updates are enabled)
                $requiredUpdates = getSettingByScope($connection2, 'Data Updater', 'requiredUpdates');
                if ($requiredUpdates == 'Y') {
                    if (isActionAccessible($guid, $connection2, '/modules/Data Updater/data_updates.php')) { // Can we update data?
                        $redirectByRoleCategory = getSettingByScope(
                            $connection2,
                            'Data Updater',
                            'redirectByRoleCategory'
                        );
                        $redirectByRoleCategory = explode(',', $redirectByRoleCategory);

                        // Are we the right role category?
                        if (in_array($roleCategory, $redirectByRoleCategory)) {
                            $gateway = new DataUpdaterGateway($pdo);

                            $updatesRequiredCount = $gateway->countAllRequiredUpdatesByPerson($session->get('gibbonPersonID'));

                            if ($updatesRequiredCount > 0) {
                                $URL = $session->get('absoluteURL').'/index.php?q=/modules/Data Updater/data_updates.php&redirect=true';
                                $session->set('pageLoads', null);
                                header("Location: {$URL}");
                                exit;
                            }
                        }
                    }
                }
            }
        }

        /**
         * SIDEBAR SETUP
         *
         * TODO: move all of the sidebar session variables to the $page->addSidebarExtra() method.
         */

        // Set sidebar extra content values via GibbonSession.
        $session->set('sidebarExtra', '');
        $session->set('sidebarExtraPosition', 'top');

        // Check the current Action 'entrySidebar' to see if we should display a sidebar
        $showSidebar = $page->getAction()
            ? $page->getAction()['entrySidebar'] != 'N'
            : true;

        // Override showSidebar if the URL 'sidebar' param is explicitly set
        if ($request->query->has('sidebar')) {
            $showSidebar = strtolower($request->query->get('sidebar')) !== 'false';
        }

        /**
         * SESSION TIMEOUT
         *
         * Set session duration, which will be passed via JS config to setup the
         * session timeout. Ensures a minimum session duration of 1200.
         */
        $sessionDuration = -1;
        if ($isLoggedIn) {
            $sessionDuration = $session->get('sessionDuration');
            $sessionDuration = max(intval($sessionDuration), 1200);
        }

        /**
         * LOCALE
         *
         * Sets the i18n locale for jQuery UI DatePicker (if the file exists, otherwise
         * falls back to en-GB)
         */
        $localeCode = str_replace('_', '-', $session->get('i18n')['code']);
        $localeCodeShort = substr($session->get('i18n')['code'], 0, 2);
        $localePath = $session->get('absolutePath').'/build/jquery-ui/i18n/jquery.ui.datepicker-%1$s.js';

        $datepickerLocale = 'en-GB';
        if ($localeCode === 'en-US' || is_file(sprintf($localePath, $localeCode))) {
            $datepickerLocale = $localeCode;
        } elseif (is_file(sprintf($localePath, $localeCodeShort))) {
            $datepickerLocale = $localeCodeShort;
        }

        // Allow the URL to override system default from the i18l param
        if ($request->query->has('i18n') && $gibbon->locale->getLocale() != $request->query->get('i18n')) {
            $data = ['code' => $request->query->get('i18n')];
            $sql = "SELECT * FROM gibboni18n WHERE code=:code LIMIT 1";

            if ($result = $pdo->selectOne($sql, $data)) {
                setLanguageSession($guid, $result, false);
                $gibbon->locale->setLocale($request->query->get('i18n'));
                $gibbon->locale->setTextDomain($pdo);
                $cacheLoad = true;
            }
        }

        /**
         * JAVASCRIPT
         *
         * The config array defines a set of PHP values that are encoded and passed to
         * the setup.js file, which handles initialization of js libraries.
         */
        $javascriptConfig = [
            'config' => [
                'datepicker' => [
                    'locale' => $datepickerLocale,
                ],
                'thickbox' => [
                    'pathToImage' => $session->get('absoluteURL').'/build/thickbox/loadingAnimation.gif',
                ],
                'tinymce' => [
                    'valid_elements' => getSettingByScope($connection2, 'System', 'allowableHTML'),
                ],
                'sessionTimeout' => [
                    'sessionDuration' => $sessionDuration,
                    'message' => __('Your session is about to expire: you will be logged out shortly.'),
                ]
            ],
        ];

        /**
         * There are currently a handful of scripts that must be in the page <HEAD>.
         * Otherwise, the preference is to add javascript to the 'foot' at the bottom
         * of the page, which speeds up rendering by deferring their execution until
         * after all content has loaded.
         */

        // Set page scripts: head
        $page->scripts->addMultiple([
            'lv'             => 'build/LiveValidation/livevalidation_standalone.compressed.js',
            'jquery'         => 'build/jquery/jquery.js',
            'jquery-migrate' => 'build/jquery/jquery-migrate.min.js',
            'jquery-ui'      => 'build/jquery-ui/js/jquery-ui.min.js',
            'jquery-time'    => 'build/jquery-timepicker/jquery.timepicker.min.js',
            'jquery-chained' => 'build/chained/jquery.chained.min.js',
            'core'           => 'build/core/core.min.js',
        ], ['context' => 'head']);

        // Set page scripts: foot - jquery
        $page->scripts->addMultiple([
            'jquery-latex'    => 'build/jquery-jslatex/jquery.jslatex.js',
            'jquery-form'     => 'build/jquery-form/jquery.form.js',
            //This sets the default for en-US, or changes for none en-US
            'jquery-date'     => $datepickerLocale === 'en-US' ? '' : 'build/jquery-ui/i18n/jquery.ui.datepicker-'.$datepickerLocale.'.js',
            'jquery-autosize' => 'build/jquery-autosize/jquery.autosize.min.js',
            'jquery-timeout'  => 'build/jquery-sessionTimeout/jquery.sessionTimeout.min.js',
            'jquery-token'    => 'build/jquery-tokeninput/src/jquery.tokeninput.js',
        ], ['context' => 'foot']);

        // Set page scripts: foot - misc
        $thickboxInline = 'var tb_pathToImage="'.$session->get('absoluteURL').'/build/thickbox/loadingAnimation.gif";';
        $page->scripts->add('thickboxi', $thickboxInline, ['type' => 'inline']);
        $page->scripts->addMultiple([
            'thickbox' => 'build/thickbox/thickbox-compressed.js',
            'tinymce'  => 'build/tinymce/tinymce.min.js',
        ], ['context' => 'foot']);

        // Set page scripts: foot - core
        $page->scripts->add('core-config', 'window.Gibbon = '.json_encode($javascriptConfig).';', ['type' => 'inline']);
        $page->scripts->add('core-setup', 'build/core/setup.js');

        // Register scripts available to the core, but not included by default
        $page->scripts->register('chart', 'build/Chart.js/2.0/Chart.bundle.min.js', ['context' => 'head']);

        // Set system analytics code from session cache
        $page->addHeadExtra($session->get('analytics'));

        /**
         * STYLESHEETS & CSS
         */
        $page->stylesheets->addMultiple([
            'jquery-ui'    => 'build/jquery-ui/css/blitzer/jquery-ui.css',
            'jquery-time'  => 'build/jquery-timepicker/jquery.timepicker.css',
            'thickbox'     => 'build/thickbox/thickbox.css',
        ], ['weight' => -1]);

        $page->theme->stylesheets->add('theme', '/themes/'.$session->get('gibbonThemeName', 'Default').'/css/main.css', ['weight' => 1]);

        // Add right-to-left stylesheet
        if ($session->get('i18n')['rtl'] == 'Y') {
            $page->theme->stylesheets->add('theme-rtl', '/themes/'.$session->get('gibbonThemeName', 'Default').'/css/main_rtl.css', ['weight' => 1]);
        }

        // Set personal, organisational or theme background     
        if (getSettingByScope($connection2, 'User Admin', 'personalBackground') == 'Y' && $session->has('personalBackground')) {
            $backgroundImage = htmlPrep($session->get('personalBackground'));
            $backgroundScroll = 'repeat scroll center top';
        } else if ($session->has('organisationBackground')) {
            $backgroundImage = $session->get('absoluteURL').'/'.$session->get('organisationBackground');
            $backgroundScroll = 'repeat fixed center top';
        } else {
            $backgroundImage = $session->get('absoluteURL').'/themes/'.$session->get('gibbonThemeName', 'Default').'/img/backgroundPage.jpg';
            $backgroundScroll = 'repeat fixed center top';
        }

        $page->stylesheets->add(
            'personal-background',
            'body { background: url('.$backgroundImage.') '.$backgroundScroll.' #626cd3!important; }',
            ['type' => 'inline']
        );

        $page->stylesheets->add('theme-dev', 'build/core/theme.min.css');
        $page->stylesheets->add('core', 'build/core/core.min.css', ['weight' => 10]);

        /**
         * USER CONFIGURATION
         *
         * This should be moved to a one-time process to run after login, which can be
         * handled by HTTP middleware.
         */

        // Try to auto-set user's calendar feed if not set already
        if ($session->exists('calendarFeedPersonal') && $session->exists('googleAPIAccessToken')) {
            if (!$session->has('calendarFeedPersonal') && $session->has('googleAPIAccessToken')) {
                $service = $this->container->get('Google_Service_Calendar');
                try {
                    $calendar = $service->calendars->get('primary');
                } catch (\Google_Service_Exception $e) {}

                if (!empty($calendar['id'])) {
                    $session->set('calendarFeedPersonal', $calendar['id']);
                    $this->container->get(UserGateway::class)->update($session->get('gibbonPersonID'), [
                        'calendarFeedPersonal' => $calendar['id'],
                    ]);
                }
            }
        }

        // Get house logo and set session variable, only on first load after login (for performance)
        if ($session->get('pageLoads') == 0 and $session->has('username') and !$session->has('gibbonHouseIDLogo')) {
            $dataHouse = array('gibbonHouseID' => $session->get('gibbonHouseID'));
            $sqlHouse = "SELECT `logo`, `name` FROM `gibbonHouse` WHERE `gibbonHouseID`=:gibbonHouseID";
            $house = $pdo->selectOne($sqlHouse, $dataHouse);

            if (!empty($house)) {
                $session->set('gibbonHouseIDLogo', $house['logo']);
                $session->set('gibbonHouseIDName', $house['name']);
            }
        }

        // Show warning if not in the current school year
        // TODO: When we implement routing, these can become part of the HTTP middleware.
        if ($isLoggedIn) {
            if ($session->get('gibbonSchoolYearID') != $session->get('gibbonSchoolYearIDCurrent')) {
                $page->addWarning('<b><u>'.sprintf(__('Warning: you are logged into the system in school year %1$s, which is not the current year.'), $session->get('gibbonSchoolYearName')).'</b></u>'.__('Your data may not look quite right (for example, students who have left the school will not appear in previous years), but you should be able to edit information from other years which is not available in the current year.'));
            }
        }

        /**
         * RETURN PROCESS
         *
         * Adds an alert to the index based on the URL 'return' parameter.
         *
         * TODO: Remove all returnProcess() from pages. We could add a method to the
         * Page class to allow them to register custom messages, or use GibbonSession flash
         * to add the message directly from the Process pages.
         */
        if (!$session->has('address') && !empty($request->query->get('return'))) {
            $customReturns = [
                'success1' => __('Password reset was successful: you may now log in.')
            ];

            if ($alert = returnProcessGetAlert($request->query->get('return'), '', $customReturns)) {
                $page->addAlert($alert['context'], $alert['text']);
            }
        }

        /**
         * MENU ITEMS & FAST FINDER
         *
         * TODO: Move this somewhere more sensible.
         */
        if ($isLoggedIn) {
            if ($cacheLoad || !$session->has('fastFinder')) {
                $templateData = getFastFinder($connection2, $guid);
                $templateData['enrolmentCount'] = $this->providerFactory::getRepository(StudentEnrolment::class)->getStudentEnrolmentCount($session->get('gibbonSchoolYearID'));

                $fastFinder = $page->fetchFromTemplate('legacy/finder.html.twig', $templateData);
                $session->set('fastFinder', $fastFinder);
            }

            $moduleGateway = $this->providerFactory->getProvider(Module::class);

            if ($cacheLoad || !$session->has('menuMainItems')) {
                $menuMainItems = $moduleGateway->selectModulesByRole($session->get('gibbonRoleIDCurrent'));

                foreach ($menuMainItems as $category => &$items) {
                    foreach ($items as &$item) {
                        $modulePath = '/modules/'.$item['name'];
                        $entryURL = isActionAccessible($guid, $connection2, $modulePath.'/'.$item['entryURL'])
                            ? $item['entryURL']
                            : $item['alternateEntryURL'];

                        $item['url'] = $session->get('absoluteURL').'/index.php?q='.$modulePath.'/'.$entryURL;
                    }
                }

                $session->set('menuMainItems', $menuMainItems);
            }

            if ($page->getModule()) {
                $currentModule = $page->getModule()->getName();
                $menuModule = $session->get('menuModuleName');

                if ($cacheLoad || !$session->has('menuModuleItems') || $currentModule != $menuModule) {
                    $menuModuleItems = $moduleGateway->selectModuleActionsByRole($page->getModule()->getID(), $session->get('gibbonRoleIDCurrent'));
                } else {
                    $menuModuleItems = $session->get('menuModuleItems');
                }

                // Update the menu items to indicate the current active action
                foreach ($menuModuleItems as $category => &$items) {
                    foreach ($items as &$item) {
                        $urlList = array_map('trim', explode(',', $item['URLList']));
                        $item['active'] = in_array($session->get('action'), $urlList);
                        $item['url'] = $session->get('absoluteURL').'/index.php?q=/modules/'
                            .$item['moduleName'].'/'.$item['entryURL'];
                    }
                }

                $session->set('menuModuleItems', $menuModuleItems);
                $session->set('menuModuleName', $currentModule);
            } else {
                $session->forget(['menuModuleItems', 'menuModuleName']);
            }
        }

        /**
         * TEMPLATE DATA
         *
         * These values are merged with the Page class settings & content, then passed
         * into the template engine for rendering. They're a work in progress, but once
         * they're more finalized we can document them for theme developers.
         */

        $page->addData([
            'isLoggedIn'        => $isLoggedIn,
            'gibbonThemeName'   => $session->get('gibbonThemeName', 'Default'),
            'gibbonHouseIDLogo' => $session->get('gibbonHouseIDLogo'),
            'organisationLogo'  => $session->get('organisationLogo'),
            'minorLinks'        => $this->header->getMinorLinks($cacheLoad),
            'notificationTray'  => $this->header->getNotificationTray($cacheLoad),
            'sidebar'           => $showSidebar,
            'version'           => $gibbon->getVersion(),
            'versionName'       => 'v'.$gibbon->getVersion().($session->get('cuttingEdgeCode') == 'Y'? 'dev' : ''),
            'rightToLeft'       => $session->get('i18n')['rtl'] == 'Y',
            'locale'            => $session->get('i18n')['code'],
            'wrapVersion'       => $gibbon->wrapVersion,
        ]);
        if ($isLoggedIn) {
            $page->addData([
                'menuMain'   => $session->get('menuMainItems', []),
                'menuModule' => $session->get('menuModuleItems', []),
                'fastFinder' => $session->get('fastFinder'),
            ]);
        }

        /**
         * GET PAGE CONTENT
         *
         * TODO: move queries into Gateway classes.
         * TODO: rewrite dashboards as template files.
         */
        if (!$session->has('address')) {

            // Welcome message
            if (!$isLoggedIn) {
                // Create auto timeout message
                if ($request->query->has('timeout') && $request->query->get('timeout') === 'true') {
                    $page->addWarning(__('Your session expired, so you were automatically logged out of the system.'));
                }

                $templateData = [
                    'indexText'                 => $session->get('indexText'),
                    'organisationName'          => $session->get('organisationName'),
                    'publicStudentApplications' => getSettingByScope($connection2, 'Application Form', 'publicApplications') == 'Y',
                    'publicStaffApplications'   => getSettingByScope($connection2, 'Staff Application Form', 'staffApplicationFormPublicApplications') == 'Y',
                    'makeDepartmentsPublic'     => getSettingByScope($connection2, 'Departments', 'makeDepartmentsPublic') == 'Y',
                    'makeUnitsPublic'           => getSettingByScope($connection2, 'Planner', 'makeUnitsPublic') == 'Y',
                ];

                // Get any elements hooked into public home page, checking if they are turned on
                $hooks = $this->providerFactory::getRepository(Hook::class)->findBy(['type' => 'Public Home Page'],['name' => 'ASC']);
                $templateData['indexHooks'] = [];

                foreach ($hooks as $hook) {
                    $options = unserialize(str_replace("'", "\'", $hook->getOptions()));
                    $check = $this->providerFactory->getProvider(Setting::class)->getSettingByScope($options['toggleSettingScope'], $options['toggleSettingName']);
                    if ($check == $options['toggleSettingValue']) { // If its turned on, display it
                        $options['text'] = stripslashes($options['text']);
                        $templateData['indexHooks'][] = $options;
                    }
                }

                $page->writeFromTemplate('legacy\welcome.html.twig', $templateData);
            } else {
                // Custom content loader
                if (!$session->exists('index_custom.php')) {
                    $globals = [
                        'guid'        => $guid,
                        'connection2' => $connection2,
                    ];

                    $session->set('index_custom.php', $page->fetchFromFile('./index_custom.php', $globals));
                }

                if ($session->has('index_custom.php')) {
                    $page->write($session->get('index_custom.php'));
                }

                // DASHBOARDS!
                $category = getRoleCategory($session->get('gibbonRoleIDCurrent'), $connection2);

                switch ($category) {
                    case 'Parent':
                        $page->write($this->container->get(ParentDashboard::class)->getOutput());
                        break;
                    case 'Student':
                        $page->write($this->container->get(StudentDashboard::class)->getOutput());
                        break;
                    case 'Staff':
                        $page->write($this->staffDashboard->getOutput());
                        break;
                    default:
                        $page->write('<div class="error">'.__('Your current role type cannot be determined.').'</div>');
                }
            }
        } else {
            $address = trim($page->getAddress(), ' /');
            if ($page->isAddressValid($address) == false) {
                $content = GibbonManager::getContainer()->get('twig')->render('legacy/error.html.twig',
                    [
                        'extendedError' => GibbonManager::getContainer()->get('translator')->trans('Illegal address detected: access denied.', [], 'gibbon'),
                        'extendedParams' => ['%address%' => $address],
                        'manager' => $this,
                    ]
                );
                return new Response($content);

                $page->addError(__('Illegal address detected: access denied.'));
            } else {
                // Pass these globals into the script of the included file, for backwards compatibility.
                // These will be removed when we begin the process of ooifying action pages.
                $globals = [
                    'guid'        => $guid,
                    'gibbon'      => $gibbon,
                    'version'     => $gibbon->getVersion(),
                    'pdo'         => $pdo,
                    'connection2' => $connection2,
//                    'autoloader'  => $autoloader,
                    'container'   => GibbonManager::getContainer(),
                    'page'        => $page,
                ];

                $fullAddress = realpath(__DIR__.'/../../Gibbon/'.$address);

                if (false !== $fullAddress) {
                    $page->writeFromFile($fullAddress, $globals);
                } else {
                    $content = GibbonManager::getContainer()->get('twig')->render('legacy/error.html.twig',
                        [
                            'extendedError' => 'The page at address %address% was not found.',
                            'extendedParams' => ['%address%' => $address],
                            'manager' => $globals,
                        ]
                    );
                    return new Response($content);
                }
            }
        }

        /**
         * GET SIDEBAR CONTENT
         *
         * TODO: rewrite the Sidebar class as a template file.
         */
        if ($showSidebar) {
            $page->addSidebarExtra($session->get('sidebarExtra'));
            $session->set('sidebarExtra', '');

            $page->addData([
                'sidebarContents' => $this->sidebar->getOutput(),
                'sidebarPosition' => $session->get('sidebarExtraPosition'),
            ]);
        }

        /**
         * DONE!!
         */
        $content = $page->render('legacy\index.html.twig');
        chdir($cwd);
        return new Response($content);
    }
}