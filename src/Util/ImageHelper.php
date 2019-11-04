<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 4/11/2019
 * Time: 09:29
 */

namespace App\Util;


use App\Entity\Setting;
use App\Provider\ProviderFactory;

class ImageHelper
{
    /**
     * @var null|string
     */
    private static $absolutePath;

    /**
     * @var null|string
     */
    private static $absoluteURL;

    /**
     * getAbsolutePath
     * @return string|null
     */
    public static function getAbsolutePath(): ?string
    {
        if (null === self::$absolutePath) {
            self::$absolutePath = ProviderFactory::create(Setting::class)->getSettingByScopeAsString('System', 'absolutePath');
        }
        return self::$absolutePath;
    }

    /**
     * getAbsoluteURL
     * @return string|null
     */
    public static function getAbsoluteURL(): ?string
    {
        if (null === self::$absoluteURL) {
            self::$absoluteURL = ProviderFactory::create(Setting::class)->getSettingByScopeAsString('System', 'absoluteURL');
        }
        return self::$absoluteURL;
    }

    /**
     * getAbsoluteImageURL
     * @param string $type
     * @param string $link
     */
    public static function getAbsoluteImageURL(string $type, string $link)
    {
        if ($type === 'Link')
            return $link;

        $link = ltrim(str_replace(self::getAbsolutePath(), '', $link), '/\\');
        return self::getAbsoluteURL() . '/' . $link;
    }
}