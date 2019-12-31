<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * User: craig
 * Date: 25/08/2019
 * Time: 15:55
 */

namespace App\Util;

use Symfony\Component\HttpFoundation\File\File;

/**
 * Class FileHelper
 * @package App\Util
 */
class FileHelper
{
    /**
     * @var string
     */
    private static $absoluteUrl;

    /**
     * @var string
     */
    private static $targetPath;

    /**
     * @var string
     */
    private static $publicPath;

    /**
     * FileHelper constructor.
     */
    public function __construct(string $absoluteUrl, string $targetPath, string $publicPath)
    {
        self::setAbsoluteUrl($absoluteUrl);
        self::setTargetPath($targetPath);
        self::setPublicPath($publicPath);
    }

    /**
     * @return string
     */
    public static function getAbsoluteUrl(): string
    {
        return self::$absoluteUrl;
    }

    /**
     * @param string $absoluteUrl
     */
    public static function setAbsoluteUrl(string $absoluteUrl): void
    {
        self::$absoluteUrl = $absoluteUrl;
    }

    /**
     * @return mixed
     */
    public static function getTargetPath()
    {
        return realpath(rtrim(self::$publicPath, '/') . DIRECTORY_SEPARATOR . trim(self::$targetPath, '/'));
    }

    /**
     * @param mixed $targetPath
     */
    public static function setTargetPath($targetPath): void
    {
        self::$targetPath = $targetPath;
    }

    /**
     * @return string
     */
    public static function getPublicPath(): string
    {
        return self::$publicPath;
    }

    /**
     * @param string $publicPath
     */
    public static function setPublicPath(string $publicPath): void
    {
        self::$publicPath = $publicPath;
    }

    /**
     * moveFile
     * @param string|File $source
     * @param string $preName
     * @param string $extension
     * @param bool $asUrl
     * @param string|null $target
     * @return string
     */
    public static function moveFile($source, string $preName, ?string $extension, bool $asUrl = false, ?string $target = null): string
    {
        if ($source instanceof File)
        {
            $extension = $source->getExtension();
            $source = $source->getRealPath();
        }

        $target = self::getTargetPath() . DIRECTORY_SEPARATOR . ($target ?: substr(uniqid($preName, true), 0, 32)) . '.' . $extension;

        copy($source, $target);
        unlink($source);

        return $asUrl ? rtrim(self::$absoluteUrl, '/') . '/' . ltrim($target, '/'): $target;
    }
}