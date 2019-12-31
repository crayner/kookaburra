<?php
/**
 * Created by PhpStorm.
 *
 * bilby
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * User: craig
 * Date: 4/07/2019
 * Time: 14:38
 */

namespace App\Manager;


use Gibbon\Domain\System\Module;

class ViewFactory
{
    /**
     * @var array|null
     */
    private static $action;

    /**
     * @var Module|null
     */
    private static $module;

    /**
     * @return array|null
     */
    public static function getAction(): ?array
    {
        return self::$action;
    }

    /**
     * @param array|null $action
     */
    public static function setAction(?array $action): void
    {
        self::$action = $action;
    }

    /**
     * @return Module|null
     */
    public static function getModule(): ?Module
    {
        return self::$module;
    }

    /**
     * @param Module|null $module
     */
    public static function setModule(?Module $module): void
    {
        self::$module = $module;
    }
}