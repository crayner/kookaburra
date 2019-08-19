<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 16/08/2019
 * Time: 10:25
 */

namespace App\Form;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\FormView;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class CollectionTemplate
 * @package App\Form
 */
class CollectionTemplate implements CollectionTemplateInterface
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var FormView
     */
    private $formView;

    /**
     * ReactCollectionTrait constructor.
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * translate
     * @param string $id
     * @param array $params
     * @param string|null $domain
     * @return string
     */
    private function translate(string $id, array $params = [], string $domain = null)
    {
        if (null === $domain && false === $this->getTranslationDomain())
            return $id;
        return $this->translator->trans($id, $params, $domain ?: $this->getTranslationDomain());
    }

    /**
     * getTranslationDomain
     */
    private function getTranslationDomain()
    {
        return $this->formView->vars['translation_domain'];
    }

    /**
     * setFormView
     * @param FormView $formView
     * @return ReactCollectionTrait
     */
    public function setFormView(FormView $formView): self
    {
        $this->formView = $formView;
        return $this;
    }

    /**
     * getElementTemplates
     */
    public function getTemplate()
    {
        $x = new ArrayCollection();
        $x->set('input-text', $this->inputText());
        $x->set('input-url', $this->inputUrl());
        $x->set('input-file', $this->inputFile());
        $x->set('defaults', $this->formDefaults());
        $x->set('input-hidden', $this->inputHidden());
        $x->set('choice', $this->choice());
        return $x;
    }

    /**
     * inputText
     * @return array
     */
    private function inputText(): array
    {
        return [
            'element' => 'input',
            'type' => 'text',
            'style' => 'row',
            'row' => [
                'class' => 'flex flex-col sm:flex-row justify-between content-center p-0',
            ],
            'columns' => [
                [
                    'class' => 'flex flex-col flex-grow justify-center -mb-1 sm:mb-0  px-2 border-b-0 sm:border-b border-t-0',
                    'content' => [
                        'label',
                        'help',
                    ]
                ],
                [
                    'class' => 'w-full max-w-full sm:max-w-xs flex justify-end items-center px-2 border-b-0 sm:border-b border-t-0',
                    'content' => [
                        'widget',
                        'errors',
                    ]

                ],
            ],
            'class' => 'w-full',
            'wrapper' => [
                'class' => 'flex-1 relative',
            ],
        ];
    }

    /**
     * formDefaults
     * @return array
     */
    private function formDefaults()
    {
        return [
            'label' => [
                'class' => 'inline-block mt-4 sm:my-1 sm:max-w-xs font-bold text-sm sm:text-xs',
            ],
            'help' => [
                'class' => 'text-xxs text-gray-600 italic font-normal mt-1 sm:mt-0',
            ],
            'input' => [
                'class' => 'w-full',
            ],
            'select' => [
                'class' => 'w-full',
            ],
            'wrapper' => [
                'class' => 'flex-1 relative',
            ],
        ];
    }

    /**
     * inputUrl
     * @return array
     */
    private function inputUrl()
    {
        $template = $this->inputText();
        $template['type'] = 'url';
        return $template;
    }

    /**
     * inputUrl
     * @return array
     */
    private function inputFile()
    {
        $template = $this->inputText();
        $template['type'] = 'file';
        $template['existingFile'] = $this->translate('Current', [], 'kookaburra');
        return $template;
    }

    /**
     * inputText
     * @return array
     */
    private function inputHidden(): array
    {
        return [
            'element' => 'input',
            'type' => 'hidden',
            'style' => 'widget',
            'wrapper' => false,
        ];
    }

    /**
     * inputText
     * @return array
     */
    private function choice(): array
    {
        $template = $this->inputText();
        $template['element'] = 'select';
        unset($template['type']);
        return $template;
;    }
}