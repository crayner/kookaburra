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


use Gibbon\Domain\DataUpdater\DataUpdaterGateway;
use Gibbon\View\Page;
use PDOException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class LegacyManager
 * @package App\Manager
 */
class LegacyManager
{
    /**
     * @var Request
     */
    private $request;

    /**
     * LegacyManager constructor.
     * @param RequestStack $stack
     */
    public function __construct(RequestStack $stack)
    {
        $this->request = $stack->getCurrentRequest();
    }

    /**
     * execute
     * @param Request $request
     */
    public function execute(Request $request, Page $page)
    {
        $session = $request->getSession();
        $gibbon = GibbonManager::getGibbon();
        $guid = GibbonManager::getGuid();
        $connection2 = GibbonManager::getConnection();

        $isLoggedIn = $session->has('username') && $session->has('gibbonRoleIDCurrent');

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

        $_SESSION[$guid]['a_test'] = 'Craig';

        /**
         * SYSTEM SETTINGS
         *
         * Checks to see if system settings are set from database. If not, tries to
         * load them in. If this fails, something horrible has gone wrong ...
         *
         * TODO: Move this to the Session creation logic.
         * TODO: Handle the exit() case with a pre-defined error template.
         */
        if (!$session->has('systemSettingsSet')) {
            getSystemSettings($guid, $connection2);

            if (!$session->has('systemSettingsSet')) {
                return GibbonManager::returnErrorResponse('System Settings are not set: the system cannot be displayed.');
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
                $URL = $session->get('absoluteURL').'/index.php?q=preferences.php';
                $URL = $URL.'&forceReset=Y';
                return new RedirectResponse($URL);
            }
        }
/*
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
                        $studentSelfRegistrationIPAddresses = getSettingByScope(
                            $connection2,
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
                                    } catch (PDOException $e) {
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
*/

        /**
         * SIDEBAR SETUP
         *
         * TODO: move all of the sidebar session variables to the $page->addSidebarExtra() method.
         */

        // Set sidebar extra content values via Session.
        $session->set('sidebarExtra', '');
        $session->set('sidebarExtraPosition', 'top');

        // Check the current Action 'entrySidebar' to see if we should display a sidebar
        $showSidebar = $page->getAction()
            ? $page->getAction()['entrySidebar'] != 'N'
            : true;

        // Override showSidebar if the URL 'sidebar' param is explicitly set
        if ($this->request->query->has('sidebar')) {
            $showSidebar = strtolower($this->request->query->get('sidebar')) !== 'false';
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
    }
}