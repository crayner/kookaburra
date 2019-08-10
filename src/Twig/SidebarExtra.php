<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 1/08/2019
 * Time: 09:50
 */

namespace App\Twig;

use Gibbon\Exception;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class SidebarExtra
 * @package App\Twig
 */
class SidebarExtra
{
    /**
     * @var string
     */
    private $type;

    /**
     * @var array
     */
    private static $typeList = [
        'image',
        'myClasses',
    ];

    /**
     * @var array
     */
    private $content;

    /**
     * @var string
     */
    private $position = 'top';

    /**
     * @var integer
     */
    private $priority;

    /**
     * SidebarExtra constructor.
     * @param string $type
     * @param array $content
     * @throws Exception
     */
    public function __construct(string $type, array $content)
    {
        $this->setType($type);
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return array
     */
    public static function getTypeList(): array
    {
        return self::$typeList;
    }

    /**
     * Type.
     *
     * @param string $type
     * @return SidebarExtra
     */
    public function setType(string $type): SidebarExtra
    {
        if (!in_array($type, self::getTypeList()))
            throw new Exception(sprintf('The sidebar accepts content of type [%s]. "%s" is not a valid type!', implode(', ', self::getTypeList()), $type));
        $this->type = $type;
        return $this;
    }

    /**
     * @return array
     */
    public function getContent(): array
    {
        switch ($this->type) {
            case 'image':
                return $this->resolveImage();
                break;
        }
        return $this->content;
    }

    /**
     * Content.
     *
     * @param array $content
     * @return SidebarExtra
     */
    public function setContent(array $content): SidebarExtra
    {
        $this->content = $content;
        return $this;
    }

    /**
     * resolveImage
     * @return array
     */
    private function resolveImage(): array
    {
        $resolver = new OptionsResolver();
        $resolver->setRequired(
            [
                'class',
                'asset',
            ]
        );
        $resolver->setAllowedTypes('class', ['string']);
        $resolver->setAllowedTypes('asset', ['string']);
        return $resolver->resolve($this->content);
    }

    /**
     * @return string
     */
    public function getPosition(): string
    {
        return $this->position;
    }

    /**
     * Position.
     *
     * @param string $position
     * @return SidebarExtra
     */
    public function setPosition(?string $position): SidebarExtra
    {
        $this->position = in_array($position, ['top', 'bottom']) ? $position : 'top';
        return $this;
    }

    /**
     * @return int
     */
    public function getPriority(): int
    {
        return $this->priority;
    }

    /**
     * setPriority
     *
     * @param int|null $priority
     * @return SidebarExtra
     */
    public function setPriority(int $priority): SidebarExtra
    {
        $this->priority = $priority;
        return $this;
    }
}