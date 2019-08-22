<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 21/08/2019
 * Time: 07:41
 */

namespace App\Form\Extension;

use App\Form\Templates\ReactTemplateInterface;
use App\Manager\ScriptManager;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class ReactFormExtension
 * @package App\Form\Extension
 */
class ReactFormExtension extends AbstractTypeExtension
{
    /**
     * @var ReactTemplateInterface
     */
    private $template;

    /**
     * ReactFormExtension constructor.
     * @param ScriptManager $scriptManager
     * @param TranslatorInterface $translator
     */
    public function __construct(ScriptManager $scriptManager, TranslatorInterface $translator)
    {
        $this->scriptManager = $scriptManager;
        $this->translator = $translator;
    }

    /**
     * configureOptions
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('use_react', false);
        $resolver->setDefault('template_style', 'table');
        $resolver->setDefault('row_style', 'standard');
        $resolver->setDefault('column_count', 2);

        $resolver->setAllowedValues('use_react', [true,false]);
        $resolver->setAllowedValues('template_style', ['table']);
        $resolver->setAllowedValues('row_style', ['standard','header']);
    }

    /**
     * finishView
     * @param FormView $view
     * @param FormInterface $form
     * @param array $options
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['template_style'] = $options['template_style'];
        $view->vars['row_style'] = $options['row_style'];
        $view->vars['column_count'] = $options['column_count'];
        parent::finishView($view, $form, $options);
        if ($form->isRoot() && $options['use_react']) {
            $this->setTranslationDomain($view->vars['translation_domain']);
            $template = '\App\Form\Templates\\'.ucfirst($options['template_style']) . 'Template';
            $this->setTemplate(new $template());
            $this->buildTemplateView($view);
            $view->vars['row_style'] = 'parent';
            $view->vars['row'] = $this->template->getParentStyle();
        }
    }

    /**
     * getExtendedTypes
     * @return array|iterable
     */
    public static function getExtendedTypes()
    {
        return [FormType::class];
    }

    /**
     * @var ScriptManager
     */
    private $scriptManager;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var null|string
     */
    private $translationDomain;

    /**
     * translate
     * @param string $id
     * @param array $params
     * @param string|null $domain  Override the default messages.
     * @return string
     */
    private function translate(string $id, array $params = [], ?string $domain = 'gibbon'): string
    {
        return $this->translator->trans($id, $params, $domain);
    }

    /**
     * buildTemplateView
     * @param FormView $view
     */
    private function buildTemplateView(FormView $view)
    {
        foreach($view->children as $child)
            $this->buildTemplateView($child);

        // Merge template

        if (!isset($view->vars['row_style']))
        {
            if (in_array('submit', $view->vars['block_prefixes']))
                $view->vars['row_style'] = 'submit';
        }
        if (in_array('crsf_token', $view->vars['block_prefixes']))
            $view->vars['row_style'] = 'widget';
        $view->vars['row'] = $this->getTemplateRow($view->vars['row_style']);

        // Translation

        if (!(null === $view->vars['label'] || false === $view->vars['label']))
            $view->vars['label'] = $this->translate($view->vars['label'], $view->vars['label_translation_parameters'], $view->vars['translation_domain'] ?: $this->getTranslationDomain());

        if (isset($view->vars['help']) && !(null === $view->vars['help'] || false === $view->vars['help']))
            $view->vars['help'] = $this->translate($view->vars['help'], $view->vars['help_translation_parameters'], $view->vars['translation_domain'] ?: $this->getTranslationDomain());

        foreach($view->vars['attr'] as $attrName=>$attr)
            if (in_array($attrName, ['title', 'placeholder']))
                $view->vars['attr'][$attrName] = $this->translate($view->vars['attr'][$attrName], $view->vars['attr_translation_parameters'], $view->vars['translation_domain'] ?: $this->getTranslationDomain());

        if (isset($view->vars['placeholder']) && !(null === $view->vars['placeholder'] || false === $view->vars['placeholder']))
            $view->vars['placeholder'] = $this->translate($view->vars['placeholder'], [], $view->vars['translation_domain'] ?: $this->getTranslationDomain());

        if (isset($view->vars['choices']) && false !== $view->vars['choice_translation_domain']) {
            dd('@todo: Translate the Choices',$view);
        }

        if (! isset($view->vars['attr']['class']))
            $view->vars['attr']['class'] = 'w-full';

        if (in_array('submit', $view->vars['block_prefixes']))
        {
            $view->vars['help'] = $this->translate('denotes a required field', [], $view->vars['translation_domain'] ?: $this->getTranslationDomain());
            $view->vars['attr']['class'] = '';
        }

    }

    /**
     * getTemplateRow
     * @param string $name
     * @return array
     */
    private function getTemplateRow(string $name): array
    {
        if ($name === 'widget')
            return [];
        if ($name === 'parent')
            $name = 'get' . ucfirst($name) . 'Style';
        else
            $name = 'get' . ucfirst($name) . 'Row';
        return $this->template->$name();
    }

    /**
     * Template.
     *
     * @param ReactTemplateInterface $template
     * @return ReactFormExtension
     */
    private function setTemplate(ReactTemplateInterface $template): ReactFormExtension
    {
        $this->template = $template;
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
     * @return ReactFormExtension
     */
    public function setTranslationDomain(?string $translationDomain): ReactFormExtension
    {
        $this->translationDomain = $translationDomain;
        return $this;
    }
}