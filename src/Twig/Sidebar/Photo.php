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
 * Date: 9/11/2019
 * Time: 16:11
 */

namespace App\Twig\Sidebar;

use App\Twig\SidebarContentInterface;
use App\Twig\SidebarContentTrait;
use App\Util\ImageHelper;
use App\Util\TranslationsHelper;

class Photo implements SidebarContentInterface
{
    use SidebarContentTrait;

    /*
     * @var object
     */
    private $entity;

    /**
     * @var string
     */
    private $method;

    /**
     * @var string
     */
    private $size;

    /**
     * @var string
     */
    private $class;

    /**
     * @var string
     */
    private $position = 'top';

    /**
     * @var int
     */
    private $priority = 5;

    /**
     * @var string
     */
    private $name = 'Photo';

    /**
     * @var string
     */
    private $transDomain = 'messages';

    /**
     * @var string
     */
    private $title = '';

    /**
     * Photo constructor.
     * @param $entity
     * @param string $method
     * @param string $size
     * @param string $class
     */
    public function __construct($entity, string $method, string $size = '75', string $class = '')
    {
        $this->entity = $entity;
        $this->method = $method;
        $this->size = $size;
        $this->class = $class;
    }

    /**
     * render
     * @param array $options
     * @return string
     */
    public function render(array $options): string
    {
        if (method_exists($this->getEntity(), $this->getMethod()))
            try {
                return $this->getTwig()->render('components/photo.html.twig', [
                    'photo' => $this,
                ]);
            } catch (\Twig\Error\LoaderError | \Twig\Error\RuntimeError | \Twig\Error\SyntaxError $e) {
                return '';
            }
        return '';
    }

    /**
     * @return object
     */
    public function getEntity(): object
    {
        return $this->entity;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getSize(): string
    {
        return $this->size;
    }

    /**
     * @return string
     */
    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * @return string|bool|null
     */
    public function getTransDomain()
    {
        return $this->transDomain;
    }

    /**
     * TransDomain.
     *
     * @param string|boolean|null $transDomain
     * @return Photo
     */
    public function setTransDomain($transDomain): Photo
    {
        $this->transDomain = $transDomain;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Title.
     *
     * @param string $title
     * @return Photo
     */
    public function setTitle(string $title): Photo
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @var string|null
     */
    private $fileName;

    /**
     * @var bool|null
     */
    private $fileExists;

    /**
     * fileExists
     * @return bool
     */
    public function fileExists(): bool
    {
        if (is_null($this->fileExists)) {
            $method = $this->getMethod();
            $fileName = ImageHelper::getRelativeImageURL($this->getEntity()->$method());
            if (null === $fileName || '' === $fileName)
                return $this->fileExists = false;
            if (is_file($fileName) || $this->url_exists($fileName)) {
                $this->fileName = $fileName;
                return $this->fileExists = true;
            }
            return $this->fileExists = false;
        }
        return $this->fileExists ? true : false;
    }

    /**
     * @return string|null
     */
    public function getFileName(): ?string
    {
        if (null === $this->fileName && null === $this->fileExists)
            $this->fileExists();
        return $this->fileName;
    }

    /**
     * @var int|null
     */
    private $width;

    /**
     * getWidth
     * @return int
     */
    public function getWidth(): int
    {
        if (is_null($this->width)) {
            if (!$this->fileExists())
                return $this->width = 0;
            $info = getimagesize($this->getFileName());
            $x = $info[0] > $info[1] ? $info[0] : $info[1];
            $x = floatval(intval($this->getSize()) / $x);
            return $this->width = intval($x * $info[0]);
        }
        return $this->width;
    }

    /**
     * url_exists
     * @param $url
     * @return int
     */
    private function url_exists($url){

        if (function_exists('curl_version')) {
            $resURL = curl_init($url);
            curl_setopt($resURL, CURLOPT_HEADER, true);    // we want headers
            curl_setopt($resURL, CURLOPT_NOBODY, true);    // dont need body
            curl_setopt($resURL, CURLOPT_RETURNTRANSFER, true);    // catch output (do NOT print!)
            curl_setopt($resURL, CURLOPT_FOLLOWLOCATION, false);
            curl_exec($resURL);
            $intReturnCode = curl_getinfo($resURL, CURLINFO_HTTP_CODE);
            curl_close($resURL);
            if ($intReturnCode != 200 && $intReturnCode != 301 && $intReturnCode != 302 && $intReturnCode != 304) {
                return 0;
            } else
                return 1;
        } else {
            // do this the slow way
            return false !== file_get_contents($url);
        }
    }

    /**
     * toArray
     * @return array
     */
    public function toArray(): array
    {
        $method = $this->getMethod();
        return [
            'width' => $this->getWidth(),
            'className' => $this->getClass(),
            'title' => $this->getTransDomain() === false ? $this->getTitle() : TranslationsHelper::translate($this->getTitle(), [], $this->getTransDomain()),
            'url' => ImageHelper::getAbsoluteImageURL('File',$this->getEntity()->$method()),
            'exists' => $this->fileExists(),
        ];
    }
}