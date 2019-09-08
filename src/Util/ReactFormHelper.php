<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 7/09/2019
 * Time: 15:49
 */

namespace App\Util;


class ReactFormHelper
{
    /**
     * @var array
     */
    private static $extras = [];

    /**
     * getExtras
     * @return array
     */
    public static function getExtras(): array
    {
        return self::$extras = self::$extras ?: [];
    }

    /**
     * @param array $extras
     */
    public static function setExtras(array $extras): void
    {
        self::$extras = $extras;
    }
}