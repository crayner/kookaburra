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
     * @var ArrayCollection
     */
    private  $encoreEntryCSSFiles;

    /**
     * @var ArrayCollection
     */
    private $encoreEntryScriptTags;

    /**
     * @var ArrayCollection
     */
    private $appProps;

    /**
     * @var ArrayCollection
     */
    private $pageScripts;

    /**
     * @var ArrayCollection
     */
    private $pageStyles;

    /**
     * @var array
     */
    private $toggleScripts = [];

    /**
     * @return ArrayCollection
     */
    public function getEncoreEntryCSSFiles(): ArrayCollection
    {
        if (null === $this->encoreEntryCSSFiles)
            $this->encoreEntryCSSFiles = new ArrayCollection();

        return $this->encoreEntryCSSFiles;
    }

    /**
     * EncoreEntryCSSFiles
     *
     * @param ArrayCollection|null $encoreEntryCSSFiles
     * @return ScriptManager
     */
    public function setEncoreEntryCSSFiles(?ArrayCollection $encoreEntryCSSFiles): ScriptManager
    {
        $this->encoreEntryCSSFiles = $encoreEntryCSSFiles;
        return $this;
    }

    /**
     * addEncoreEntryCSSFile
     * @param string $name
     * @return ScriptManager
     */
    public function addEncoreEntryCSSFile(string $name): ScriptManager
    {
        if ($this->getEncoreEntryCSSFiles()->contains($name))
            return $this;

        $this->encoreEntryCSSFiles->add($name);
        return $this;
    }

    /**
     * @return array
     */
    public function getEncoreEntryScriptTags(): ArrayCollection
    {
        if (null === $this->encoreEntryScriptTags)
            $this->encoreEntryScriptTags = new ArrayCollection();

        return $this->encoreEntryScriptTags;
    }

    /**
     * EncoreEntryScriptTags.
     *
     * @param ArrayCollection $encoreEntryScriptTags
     * @return ScriptManager
     */
    public function setEncoreEntryScriptTags(?ArrayCollection $encoreEntryScriptTags): ScriptManager
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
        if ($this->getEncoreEntryScriptTags()->contains($tag))
            return $this;

        $this->encoreEntryScriptTags->add($tag);
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
     * @param array $params
     * @return ScriptManager
     */
    public function addPageScript(string $script, array $params = []): ScriptManager
    {
        $script = [$script, $params];
        if ($this->getPageScripts()->contains($script))
            return $this;

        $this->pageScripts->add($script);
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getPageStyles(): ArrayCollection
    {
        if (null === $this->pageStyles)
            $this->pageStyles = new ArrayCollection();

        return $this->pageStyles;
    }

    /**
     * PageStyles.
     *
     * @param ArrayCollection $pageStyle
     * @return ScriptManager
     */
    public function setPageStyles(ArrayCollection $pageStyles): ScriptManager
    {
        $this->pageStyles = $pageStyles;
        return $this;
    }

    /**
     * addPageScript
     * @param string $script
     * @return ScriptManager
     */
    public function addPageStyle(string $style): ScriptManager
    {
        if ($this->getPageStyles()->contains($style))
            return $this;

        $this->pageStyles->add($style);
        return $this;
    }

    /**
     * @return array
     */
    public function getToggleScripts(): array
    {
        if (! is_array($this->toggleScripts))
            $this->toggleScripts = [];

        return $this->toggleScripts;
    }

    /**
     * ToggleScripts.
     *
     * @param array $toggleScripts
     * @return ScriptManager
     */
    public function setToggleScripts(array $toggleScripts): ScriptManager
    {
        $this->toggleScripts = $toggleScripts;
        return $this;
    }

    /**
     * addToggleScript
     * @param string $idName
     */
    public function addToggleScript(string $idName): ScriptManager
    {
        if (!in_array($idName, $this->getToggleScripts()))
            $this->toggleScripts[] = $idName;

        return $this;
    }
}