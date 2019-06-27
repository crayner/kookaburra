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


use Gibbon\View\Page;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class LegacyManager
 * @package App\Manager
 */
class LegacyManager
{
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
    }
}