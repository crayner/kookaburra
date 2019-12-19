<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 14/12/2019
 * Time: 11:46
 */

namespace App\Util;

/**
 * Class StringHelper
 * @package App\Util
 */
class StringHelper
{
    /**
     * toSnakeCase
     * @param string $value
     * @return string
     */
    public static function toSnakeCase(string $value): string
    {
        return strtolower(preg_replace('/[A-Z]/', '_\\0', lcfirst(preg_replace('/[^A-Za-z0-9:]/', '', $value))));
    }
}