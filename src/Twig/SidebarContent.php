<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 8/11/2019
 * Time: 12:02
 */

namespace App\Twig;

use Doctrine\Common\Collections\ArrayCollection;
use Twig\Environment;

/**
 * Class SidebarContent
 * @package App\Twig
 */
class SidebarContent
{
    /**
     * @var ArrayCollection
     */
    private $content;

    /**
     * @var bool
     */
    private $contentSorted = false;

    /**
     * @var bool
     */
    private $noSidebar = false;

    /**
     * @var bool
     */
    private $hidden = false;

    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var bool
     */
    private $minimised = false;

    /**
     * @var array
     */
    private static $positionList = [
        'top','middle','bottom'
    ];

    /**
     * SidebarContent constructor.
     * @param Environment $twig
     */
    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * execute
     */
    public function execute(): void
    {
        $this->getContent(true);
    }

    /**
     * @return ArrayCollection
     */
    public function getContent(bool $refresh = false): ArrayCollection
    {
        $this->content = $this->content ?: new ArrayCollection();
        if ($this->content->count() > 0 && (!$this->isContentSorted() || $refresh)) {
            $iterator = $this->content->getIterator();
            $iterator->uasort(
                function ($a, $b) {
                    return $a->getPosition() . $a->getPriority() < $b->getPosition() . $b->getPriority() ? 1 : -1;
                }
            );
            $this->content  = new ArrayCollection(iterator_to_array($iterator, true));
            $this->contentSorted = true;
        }

        return $this->content;
    }

    /**
     * Content.
     *
     * @param ArrayCollection $content
     * @return SidebarContent
     */
    public function setContent(ArrayCollection $content): SidebarContent
    {
        $this->content = $content;
        $this->setContentSorted(false);
        return $this;
    }

    /**
     * addContent
     * @param SidebarContentInterface $content
     * @return SidebarContent
     */
    public function addContent(SidebarContentInterface $content): SidebarContent
    {
        if (! in_array($content->getName(), [null, ''])) {
            $content->setTwig($this->getTwig());
            $this->getContent()->set($content->getName(), $content);
            $this->setContentSorted(false);
        }
        return $this;
    }

    /**
     * getSectionContent
     * @param string $position
     * @return ArrayCollection
     */
    public function getSectionContent(string $position): ArrayCollection
    {
        return $this->getContent()->filter(function($entry) use ($position) {
            return $entry->getPosition() == $position;
        });
    }

    /**
     * hasContentMember
     * @param string $name
     * @return bool
     */
    public function hasContentMember(string $name): bool
    {
        return $this->getContent()->containsKey($name);
    }

    /**
     * getContentMember
     * @param string $name
     * @return SidebarContentInterface|null
     */
    public function getContentMember(string $name): ?SidebarContentInterface
    {
        return $this->getContent()->get($name);
    }

    /**
     * hasSectionContent
     * @param string $name
     * @return bool
     */
    public function hasSectionContent(string $name): bool
    {
        return $this->getSectionContent($name)->count() > 0;
    }

    /**
     * @return bool
     */
    public function isContentSorted(): bool
    {
        return $this->contentSorted;
    }

    /**
     * ContentSorted.
     *
     * @param bool $contentSorted
     * @return SidebarContent
     */
    public function setContentSorted(bool $contentSorted): SidebarContent
    {
        $this->contentSorted = $contentSorted;
        return $this;
    }

    /**
     * @return bool
     */
    public function isNoSidebar(): bool
    {
        return $this->noSidebar;
    }

    /**
     * NoSidebar.
     *
     * @param bool $noSidebar
     * @return SidebarContent
     */
    public function setNoSidebar(bool $noSidebar): SidebarContent
    {
        $this->noSidebar = $noSidebar;
        if ($this->hasContentMember('Module Menu')) {
            $this->getContentMember('Module Menu')->setShowSidebar(!$noSidebar);
        }
        return $this;
    }

    /**
     * isValid
     * @return bool
     */
    public function isValid(): bool
    {
        return ! $this->isNoSidebar() && $this->getContent()->count() > 0;
    }

    /**
     * @return array
     */
    public static function getPositionList(): array
    {
        return self::$positionList;
    }

    /**
     * @return bool
     */
    public function isHidden(): bool
    {
        return $this->hidden;
    }

    /**
     * Hidden.
     *
     * @param bool $hidden
     * @return SidebarContent
     */
    public function setHidden(bool $hidden): SidebarContent
    {
        $this->hidden = $hidden;
        return $this;
    }

    /**
     * getTwig
     * @return Environment
     */
    public function getTwig(): Environment
    {
        return $this->twig;
    }

    /**
     * @return bool
     */
    public function isMinimised(): bool
    {
        return $this->minimised;
    }

    /**
     * Minimised.
     *
     * @param bool $minimised
     * @return SidebarContent
     */
    public function setMinimised(bool $minimised): SidebarContent
    {
        if ($this->hasContentMember('Module Menu')) {
            $this->getContentMember('Module Menu')->setShowSidebar(!$minimised);
        }
        $this->minimised = $minimised;
        return $this;
    }
}