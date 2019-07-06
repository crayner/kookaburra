<?php
/**
 * Created by PhpStorm.
 *
 * bilby
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 6/07/2019
 * Time: 14:50
 */

namespace App\Util;


class GlobalHelper
{
    private static $page;

    /**
     * @return mixed
     */
    public static function getPage()
    {
        return self::$page;
    }

    /**
     * @param mixed $page
     */
    public static function setPage($page): void
    {
        self::$page = $page;
    }
}