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
     * @var FormView
     */
    private $form;

    /**
     * @var null|string
     */
    private $translationDomain;

    /**
     * @var null|integer
     */
    private $index;

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
     * @param string $name
     * @return Panel
     */
    public function setName(string $name): Panel
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
     * @param string $label
     * @return Panel
     */
    public function setLabel(string $label): Panel
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
        return $this->toArrayResult = $this->toArrayResult ?: [
            'name' => $this->getName(),
            'label' => $this->getLabel(),
            'disabled' => $this->isDisabled(),
            'content' => $this->getContent(),
            'translationDomain' => $this->getTranslationDomain(),
            'index' => $this->getIndex(),
            'form' => $this->formToArray($this->getForm()),
        ];
    }

    /**
     * formToArray
     * @param FormView|null $view
     * @return array|null
     */
    private function formToArray(?FormView $view): ?array
    {
        if (null === $view)
            return null;
        $result = [];

        if(isset($view->vars['basic_to_array']) && $view->vars['basic_to_array']) {
            $vars = [];
            $vars['id'] = $view->vars['id'];
            $vars['name'] = $view->vars['name'];
            $vars['full_name'] = $view->vars['full_name'];
            $vars['block_prefixes'] = $view->vars['block_prefixes'];
            $vars['errors'] = $view->vars['errors'];
            $vars['row'] = $view->vars['row'];
            $vars['row_style'] = $view->vars['row_style'];
            $vars['column'] = $view->vars['column'];
            $vars['column_style'] = $view->vars['column_style'];
            $vars['template_style'] = $view->vars['template_style'];
            $vars['column_count'] = $view->vars['column_count'];
            if (in_array('collection', $vars['block_prefixes'])) {
                $vars['allow_add'] = $view->vars['allow_add'];
                $vars['allow_delete'] = $view->vars['allow_delete'];
                $vars['element_delete_route'] = $view->vars['element_delete_route'];
                $vars['element_delete_options'] = $view->vars['element_delete_options'];
            }

            $view->vars = $vars;
        }
        foreach($view->children as $name=>$child) {
            $result['children'][$name] = $this->formToArray($child);
        }

        $vars = $view->vars;
        unset($vars['form']);
        if (isset($vars['errors']) && $vars['errors'] instanceof FormErrorIterator && $vars['errors']->count() > 0) {
            $vars['errors'] = explode("\n", str_replace('ERROR: ', '', trim($vars['errors']->__toString())));
        } else {
            $vars['errors'] = [];
        }

        if (isset( $vars['value']) && $vars['value'] instanceof File) {
         dump($vars['value']);
            $vars['value'] = $vars['value']->getRealPath();
        }

        return array_merge($vars, $result);
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

    /**
     * @return null|FormView
     */
    public function getForm(): ?FormView
    {
        return $this->form;
    }

    /**
     * Form.
     *
     * @param FormView $form
     * @return Panel
     */
    public function setForm(FormView $form): Panel
    {
        $this->form = $form;
        return $this;
    }
}