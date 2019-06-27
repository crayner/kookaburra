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



        dump($gibbon);
    }
}