<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 9/11/2019
 * Time: 16:11
 */

namespace App\Twig\Sidebar;


use App\Twig\SidebarContentInterface;
use App\Twig\SidebarContentTrait;
use App\Util\ImageHelper;

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
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function render(array $options): string
    {
        if (method_exists($this->getEntity(), $this->getMethod()))
            return $this->getTwig()->render('components/photo.html.twig', [
                'photo' => $this,
            ]);
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
     * @return string
     */
    public function getTransDomain(): string
    {
        return $this->transDomain;
    }

    /**
     * TransDomain.
     *
     * @param string $transDomain
     * @return Photo
     */
    public function setTransDomain(string $transDomain): Photo
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
     * fileExists
     * @return bool
     */
    public function fileExists(): bool
    {
        $method = $this->getMethod();
        $fileName = ImageHelper::getRelativeImageURL($this->getEntity()->$method());
        if (null === $fileName || '' === $fileName)
            return false;
        if (false !== file_get_contents($fileName))
        {
            $this->fileName = $fileName;
            return true;
        }
        return false;
    }

    /**
     * @return string|null
     */
    public function getFileName(): ?string
    {
        if (null === $this->fileName)
            $this->fileExists();
        return $this->fileName;
    }

    public function getWidth(): int
    {
        if (!$this->fileExists())
            return 0;
        $info = getimagesize($this->getFileName());
        $x = $info[0] > $info[1] ? $info[0] : $info[1];
        $x = floatval(intval($this->getSize())/$x);
        return intval($x * $info[0]);
    }
}