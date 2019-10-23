<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 19/08/2019
 * Time: 13:31
 */

namespace App\Container;

use Symfony\Component\Form\FormErrorIterator;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Class Panel
 * @package App\Container
 */
class Panel
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var bool
     */
    private $disabled = false;

    /**
     * @var string
     */
    private $label;

    /**
     * @var string
     */
    private $content;

    /**
     * @var null|string
     */
    private $translationDomain;

    /**
     * @var null|integer
     */
    private $index;

    /**
     * Panel constructor.
     * @param null|string $name
     */
    public function __construct(?string $name = null, ?string $translationDomain = null)
    {
        $this->setName($name);
        $this->setTranslationDomain($translationDomain);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Name.
     *
     * @param null|string $name
     * @return Panel
     */
    public function setName(?string $name): Panel
    {
        $this->name = $name;
        return $this->setLabel($name);
    }

    /**
     * @return bool
     */
    public function isDisabled(): bool
    {
        return $this->disabled;
    }

    /**
     * Disabled.
     *
     * @param bool $disabled
     * @return Panel
     */
    public function setDisabled(bool $disabled): Panel
    {
        $this->disabled = $disabled;
        return $this;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * Label.
     *
     * @param null|string $label
     * @return Panel
     */
    public function setLabel(?string $label): Panel
    {
        $this->label = $label;
        return $this;
    }

    /**
     * @var array|null
     */
    private $toArrayResult;

    /**
     * toArray
     * @return array
     */
    public function toArray(bool $refresh = false): array
    {
        $result =  $this->toArrayResult = $this->toArrayResult && !$refresh ? $this->toArrayResult : [
            'name' => $this->getName(),
            'label' => $this->getLabel(),
            'disabled' => $this->isDisabled(),
            'content' => $this->getContent(),
            'index' => $this->getIndex(),
            'translationDomain' => $this->getTranslationDomain(),
        ];

        return $result;
    }

    /**
     * @return null|string
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * Content.
     *
     * @param string $content
     * @return Panel
     */
    public function setContent(string $content): Panel
    {
        $this->content = $content;
        return $this;
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
     * @return Panel
     */
    public function setTranslationDomain(?string $translationDomain): Panel
    {
        $this->translationDomain = $translationDomain;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getIndex(): ?int
    {
        return $this->index;
    }

    /**
     * Index.
     *
     * @param int|null $index
     * @return Panel
     */
    public function setIndex(?int $index): Panel
    {
        $this->index = $index;
        return $this;
    }
}