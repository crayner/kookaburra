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
 * Date: 4/11/2019
 * Time: 09:29
 */

namespace App\Util;

use App\Entity\Setting;
use App\Manager\EntityInterface;
use App\Provider\ProviderFactory;
use App\Twig\Sidebar\Photo;
use Kookaburra\SystemAdmin\Manager\StringReplacementPagination;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Class ImageHelper
 * @package App\Util
 */
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

    /**
     * displayImage
     * @param $entity
     * @param string $method
     * @param string $size
     * @param string $class
     * @return Photo
     */
    public static function displayImage($entity, string $method, string $size = '75', string $class =''): Photo
    {
        return new Photo($entity, $method, $size, $class);
    }

    /**
     * getAbsoluteImagePath
     * @param string|null $link
     * @return string|null
     */
    public static function getAbsoluteImagePath(?string $link): ?string
    {
        $link = ltrim(str_replace(self::getAbsolutePath(), '', $link), '/\\');
        return self::getAbsolutePath() . '/' . str_replace('\\', '/', $link);
    }

    /**
     * deleteImage
     * @param string $image
     * @return bool
     * @throws IOException
     */
    public static function deleteImage(string $image): bool
    {
        if (!is_file(self::getAbsoluteImagePath($image)))
            return true;
        dump($image,strpos($image, '/static/'),strpos($image, '\\static\\'));
        if (strpos($image, '/static/') !== false)
            return true;
        if (strpos($image, '\\static\\') !== false)
            return true;
        $file = new File(self::getAbsoluteImagePath($image));
        $fs = new Filesystem();
        $fs->remove($file->getRealPath());
        return true;
    }

    /**
     * getRelativePath
     * Relative to the public path
     * @param string $image
     */
    public static function getRelativePath(?string $image)
    {
        if (empty($image)) return '';
        $image = str_replace([DIRECTORY_SEPARATOR, '\\', '/'], '|', $image);
        $abs = str_replace([DIRECTORY_SEPARATOR, '\\', '/'], '|', self::getAbsolutePath());

        return str_replace('|', DIRECTORY_SEPARATOR, str_replace($abs, '', $image));
    }

    /**
     * isFileInPublic
     * @param string|null $file
     * @return bool
     */
    public static function isFileInPublic(?string $file): bool
    {
        if (empty($file))
            return false;
        $file = self::getAbsoluteImagePath($file);
        return is_file($file);
    }
}