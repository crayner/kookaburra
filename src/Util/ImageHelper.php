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

use Kookaburra\SystemAdmin\Entity\Setting;
use App\Provider\ProviderFactory;
use App\Twig\Sidebar\Photo;
use Kookaburra\UserAdmin\Entity\Person;
use Kookaburra\UserAdmin\Util\UserHelper;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\RequestStack;

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
     * getAbsoluteImageURL
     * @param string $type
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
     * @param string|null $image
     * @return string
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

    /**
     * getBackgroundImage
     * @param string $default
     * @return string
     */
    public static function getBackgroundImage(string $default = '/build/static/backgroundPage.jpg'): string
    {
        if (strpos(self::$stack->getCurrentRequest()->get('_route'), 'install__') === 0 )
            return self::getAbsoluteImageURL('File', $default);

        $session = self::$stack->getCurrentRequest()->getSession();

        if ($session->has('backgroundImage'))
            return $session->get('backgroundImage');

        $user = UserHelper::getCurrentUser();
        if ($user instanceof Person && self::isFileInPublic($user->getPersonalBackground())) {
            $file = self::getAbsoluteImageURL('File',$user->getPersonalBackground());
            $session->set('backGroundImage', $file);
            return $file;
        }

        $background = ProviderFactory::create(Setting::class)->getSettingByScopeAsString('System', 'organisationBackground');
        if (self::isFileInPublic($background)) {
            $background = self::getAbsoluteImageURL('File',$background);
            $session->set('backgroundImage', $background);
            return $background;
        }

        $session->set('backgroundImage', self::getAbsoluteImageURL('File', $default));
        return $default;
    }

    /**
     * getLogoImage
     * @return string
     */
    public static function getLogoImage(): string
    {
        return self::getAbsoluteImageURL('File', '/themes/Default/img/logo.png');
    }

    /**
     * @var RequestStack
     */
    private static $stack;

    /**
     * setStack
     * @param RequestStack|null $stack
     * @return mixed
     */
    public static function setStack(RequestStack $stack = null)
    {
        self::$stack = $stack;
    }
}