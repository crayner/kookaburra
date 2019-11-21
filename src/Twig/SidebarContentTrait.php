<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 8/11/2019
 * Time: 11:56
 */

namespace App\Twig;

use Twig\Environment;

/**
 * Trait SidebarContentTrait
 * @package App\Twig
 */
trait SidebarContentTrait
{
    /**
     * @var Environment
     */
    private $twig;

    /**
     * @return Environment
     */
    public function getTwig(): Environment
    {
        return $this->twig;
    }

    /**
     * Twig.
     *
     * @param Environment $twig
     * @return SidebarContentInterface
     */
    public function setTwig(Environment $twig): SidebarContentInterface
    {
        $this->twig = $twig;
        return $this;
    }

    /**
     * @return int
     */
    public function getPriority(): int
    {
        if (! property_exists($this, 'priority'))
            return 50;
        return $this->priority;
    }

    /**
     * Priority.
     *
     * @param int $priority
     * @return SidebarContentInterface
     */
    public function setPriority(int $priority): SidebarContentInterface
    {
        if (! property_exists($this, 'priority')) {
            throw new \InvalidArgumentException(sprintf('You need to created the private property $priority before it can be set in class %s.', get_class($this)));
        }

        $this->priority = $priority;
        return $this;
    }

    /**
     * @return string
     */
    public function getPosition(): string
    {
        if (! property_exists($this, 'position'))
            return 'top';
        return $this->position;
    }

    /**
     * Position.
     *
     * @param string $position
     * @return SidebarContentInterface
     */
    public function setPosition(string $position): SidebarContentInterface
    {
        if (! property_exists($this, 'position')) {
            throw new \InvalidArgumentException(sprintf('You need to created the private property $position before it can be set in class %s.', get_class($this)));
        }

        $this->position = in_array($position, self::getPositionList()) ? $position : 'middle' ;
        return $this;
    }

    /**
     * getPositionList
     * @return array
     */
    public static function getPositionList(): array
    {
        return SidebarContent::getPositionList();
    }
    
    /**
     * getName
     * @return string
     */
    public function getName(): string
    {
        if (property_exists($this,'name'))
            return $this->name;

        return basename(__CLASS__);
    }
}