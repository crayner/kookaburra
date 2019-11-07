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
     * @param string|null $link
     * @return string|null
     */
    public static function getAbsoluteImageURL(string $type, ?string $link): ?string
    {
        if ($type === 'Link' || null === $link)
            return $link;


        $link = ltrim(str_replace(self::getAbsolutePath(), '', $link), '/\\');
        return self::getAbsoluteURL() . '/' . str_replace('\\', '/', $link);
    }

    /**
     * getRelativeImageURL
     * @param string|null $link
     * @return string|null
     */
    public static function getRelativeImageURL(?string $link): ?string
    {
        if (null === $link)
            return $link;


        $link = ltrim(str_replace(self::getAbsolutePath(), '', $link), '/\\');
        return  str_replace('\\', '/', $link);
    }

    /**
     * convertJsonToImage
     * @param string $content
     * @param string $prefix
     * @param string|null $subDirectory
     * @return string|null
     */
    public static function convertJsonToImage(string $content, string $prefix = '', ?string $subDirectory = null): ?string
    {
        $image = explode(',', $content);
        $fileContent = base64_decode($image[1]);
        $fileName = uniqid($prefix, true);
        $type = explode(';', $image[0]);
        $type = str_replace('data:image/', '', $type[0]);
        $path = realpath(__DIR__ . '/../../public/uploads/');
        $subDirectory = $subDirectory ?: date('Y/m') ;
        if (!is_dir($path . '/' . $subDirectory))
            mkdir($path . '/' . $subDirectory, '0755', true);
        $path = realpath($path . '/' . $subDirectory);
        switch($type) {
            case 'jpeg':
                $filePath = $path . '/' . $fileName . '.jpeg';
                file_put_contents($filePath, $fileContent);
                break;
            default:
                dump($type . ' is not handled.');
                $filePath = null;
        }
        return $filePath;
    }
}