<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 19/08/2019
 * Time: 11:21
 */

namespace App\Container;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class Container
 * @package App\Container
 */
class Container
{
    /**
     * @var string
     */
    private $target;

    /**
     * @var string
     */
    private $content;

    /**
     * @var ArrayCollection|Panel[]
     */
    private $panels;

    /**
     * @var null|string
     */
    private $translationDomain;

    /**
     * @var null|string
     */
    private $selectedPanel;

    /**
     * @return string
     */
    public function getTarget(): string
    {
        return $this->target;
    }

    /**
     * Target.
     *
     * @param string $target
     * @return Container
     */
    public function setTarget(string $target): Container
    {
        $this->target = $target;
        return $this;
    }

    /**
     * toArray
     * @return array
     */
    public function toArray(): array
    {
        return [
            'target' => $this->getTarget(),
            'content' => $this->getContent(),
            'panels' => $this->getPanels(),
            'translationDomain' => $this->getTranslationDomain(),
            'selectedPanel' => $this->getSelectedPanel(),
        ];
    }

    /**
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * Content.
     *
     * @param string $content
     * @return Container
     */
    public function setContent(string $content): Container
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @return Panel[]|ArrayCollection
     */
    public function getPanels()
    {
        if (null === $this->panels)
            $this->panels =  new ArrayCollection();

        return $this->panels;
    }

    /**
     * Panels.
     *
     * @param Panel[]|ArrayCollection $panels
     * @return Container
     */
    public function setPanels($panels)
    {
        $this->panels = $panels;
        return $this;
    }

    /**
     * addPanel
     * @param Panel $panel
     * @return Container
     */
    public function addPanel(Panel $panel): Container
    {
        if (null === $panel->getTranslationDomain())
            $panel->setTranslationDomain($this->getTranslationDomain());

        $panel->setIndex($this->getPanels()->count());
        $panel = $this->resolvePanel($panel);

        $this->getPanels()->set($panel->getName(), $panel->toArray());

        return $this;
    }

    /**
     * resolvePanel
     * @param Panel $panel
     * @return Panel
     */
    private function resolvePanel(Panel $panel): Panel
    {
        $resolver = new OptionsResolver();

        $resolver->setRequired(
            [
                'name',
                'label',
                'index',
            ]
        );

        $resolver->setDefaults(
            [
                'disabled' => false,
                'content' => null,
                'translationDomain' => null,
                'form' => null,
            ]
        );

        $resolver->setAllowedTypes('name', 'string');
        $resolver->setAllowedTypes('label', 'string');
        $resolver->setAllowedTypes('disabled', 'boolean');
        $resolver->setAllowedTypes('content', ['string', 'null']);
        $resolver->setAllowedTypes('index', 'integer');
        $resolver->setAllowedTypes('translationDomain', ['string', 'null']);

        $resolver->resolve($panel->toArray());

        if ('' === $panel->getName())
            throw new \InvalidArgumentException(sprintf('The panel name is empty!'));

        return $panel;
    }

    /**
     * @return string|null
     */
    public function getTranslationDomain(): ?string
    {
        return $this->translationDomain;
    }

    /**
     * TranslationDomain.
     *
     * @param string|null $translationDomain
     * @return Container
     */
    public function setTranslationDomain(?string $translationDomain): Container
    {
        $this->translationDomain = $translationDomain;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSelectedPanel(): ?string
    {
        return $this->selectedPanel ?: $this->getPanels()->first()['name'];
    }

    /**
     * SelectedPanel.
     *
     * @param string|null $selectedPanel
     * @return Container
     */
    public function setSelectedPanel(?string $selectedPanel): Container
    {
        $this->selectedPanel = $selectedPanel;
        return $this;
    }
}