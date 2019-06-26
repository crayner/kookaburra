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
    public function execute(Request $request)
    {
        dump($request);
    }
}