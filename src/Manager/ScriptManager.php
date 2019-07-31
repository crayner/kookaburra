<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 29/07/2019
 * Time: 21:09
 */

namespace App\Manager;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class ScriptManager
 * @package App\Manager
 */
class ScriptManager
{
    /**
     * @var array
     */
    private  $encoreEntryCSSFiles = [];

    /**
     * @var array
     */
    private $encoreEntryScriptTags = [];

    /**
     * @var ArrayCollection
     */
    private $appProps;

    /**
     * @var ArrayCollection
     */
    private $pageScripts;

    /**
     * @return array
     */
    public function getEncoreEntryCSSFiles(): array
    {
        return $this->encoreEntryCSSFiles;
    }

    /**
     * EncoreEntryCSSFiles.
     *
     * @param array $encoreEntryCSSFiles
     * @return ScriptManager
     */
    public function setEncoreEntryCSSFiles(array $encoreEntryCSSFiles): ScriptManager
    {
        $this->encoreEntryCSSFiles = $encoreEntryCSSFiles;
        return $this;
    }

    /**
     * @return array
     */
    public function getEncoreEntryScriptTags(): array
    {
        if (!is_array($this->encoreEntryScriptTags))
            $this->encoreEntryScriptTags = [];

        return $this->encoreEntryScriptTags;
    }

    /**
     * EncoreEntryScriptTags.
     *
     * @param array $encoreEntryScriptTags
     * @return ScriptManager
     */
    public function setEncoreEntryScriptTags(array $encoreEntryScriptTags): ScriptManager
    {
        $this->encoreEntryScriptTags = $encoreEntryScriptTags;
        return $this;
    }

    /**
     * addEncoreEntryScriptTag
     * @param string $tag
     * @return ScriptManager
     */
    public function addEncoreEntryScriptTag(string $tag): ScriptManager
    {
        if (!in_array($tag, $this->getEncoreEntryScriptTags()))
            $this->encoreEntryScriptTags[] = $tag;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getAppProps(): ArrayCollection
    {
        if (null === $this->appProps)
            $this->appProps = new ArrayCollection();

        return $this->appProps;
    }

    /**
     * AppProps.
     *
     * @param ArrayCollection $appProps
     * @return ScriptManager
     */
    public function setAppProps(ArrayCollection $appProps): ScriptManager
    {
        $this->appProps = $appProps;
        return $this;
    }

    /**
     * addAppProp
     * @param string $tag
     * @param array $props
     * @return ScriptManager
     */
    public function addAppProp(string $tag, array $props): ScriptManager
    {
        $this->addEncoreEntryScriptTag($tag);

        $this->getAppProps()->set($tag, $props);

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getPageScripts(): ArrayCollection
    {
        if (null === $this->pageScripts)
            $this->pageScripts = new ArrayCollection();

        return $this->pageScripts;
    }

    /**
     * PageScripts.
     *
     * @param ArrayCollection $pageScripts
     * @return ScriptManager
     */
    public function setPageScripts(ArrayCollection $pageScripts): ScriptManager
    {
        $this->pageScripts = $pageScripts;
        return $this;
    }

    /**
     * addPageScript
     * @param string $script
     * @return ScriptManager
     */
    public function addPageScript(string $script): ScriptManager
    {
        if ($this->getPageScripts()->contains($script))
            return $this;

        $this->pageScripts->add($script);
        return $this;
    }
}