<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 28/08/2019
 * Time: 13:40
 */

namespace App\Form\Type;

use App\Manager\EntityInterface;
use App\Manager\ScriptManager;
use App\Util\ReactFormHelper;
use App\Util\TranslationsHelper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormErrorIterator;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class ReactFormType
 * @package App\Form\Type
 */
class ReactFormType extends AbstractType
{
     /**
      * @var ScriptManager
      */
     private $scriptManager;

     /**
      * @var TranslatorInterface
      */
     private $translator;

    /**
     * ReactFormType constructor.
     * @param ScriptManager $scriptManager
     * @param TranslatorInterface $translator
     * @param ReactFormHelper $helper
     */
     public function __construct(ScriptManager $scriptManager, TranslatorInterface $translator, ReactFormHelper $helper)
     {
         $this->scriptManager = $scriptManager;
         $this->translator = $translator;
     }

     /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return FormType::class;
    }

     /**
      * configureOptions
      * @param OptionsResolver $resolver
      */
     public function configureOptions(OptionsResolver $resolver)
     {
         $resolver->setDefaults(
             [
                 'template' =>'table',
                 'panels' => 1,
                 'columns' => 2,
                 'target' => 'formContent',
             ]
         );

         $resolver->setAllowedValues('template', ['table']); // future expansion
         $resolver->setAllowedTypes('panels', ['integer']);
         $resolver->setAllowedTypes('columns', ['integer']);
     }

     /**
      * finishView
      * @param FormView $view
      * @param FormInterface $form
      * @param array $options
      */
     public function finishView(FormView $view, FormInterface $form, array $options)
     {
         $view->vars['template'] = $options['template'];
         $view->vars['panels'] = $options['panels'];
         $view->vars['columns'] = $options['columns'];
         if ($form->isRoot()) {
             $this->setTranslationDomain($view->vars['translation_domain']);
             $vars = $this->buildTemplateView($view);
             $vars['action'] = $options['action'];
             $vars['method'] = $options['method'];
         }
         $this->addTranslation('Actions');
         $this->addTranslation('File Download', [], 'messages');
         $this->addTranslation('Open Link', [], 'messages');
         $this->addTranslation('Yes/No', [], 'messages');
         $this->addTranslation('File Delete', [], 'messages');
         $this->addTranslation('Let me ponder your request', [], 'messages');
         $this->addTranslation('Add');
         $this->addTranslation('Delete');
         $this->addTranslation('Close Message');
         $this->addTranslation('There are no records to display.');

         $view->vars['toArray'] = $vars;
     }

     /**
      * @var null|string
      */
     private $translationDomain;

    /**
     * getTranslationDomain
     * @param string|null $domain
     * @return string|null
     */
    public function getTranslationDomain(?string $domain = null): ?string
    {
        return $domain !== null && $domain !== $this->translationDomain ? $domain : $this->translationDomain;
    }

    /**
     * TranslationDomain.
     *
     * @param string|null $translationDomain
     * @return ReactFormType
     */
    public function setTranslationDomain(?string $translationDomain): ReactFormType
    {
        $this->translationDomain = $translationDomain;
        return $this;
    }

     /**
      * translate
      * @param string $id
      * @param array $params
      * @param string|null $domain  Override the default messages.
      * @return string
      */
     private function translate(string $id, array $params = [], ?string $domain = 'messages'): string
     {
         return $this->translator->trans($id, $params, $domain);
     }

    /**
     * buildTemplateView
     * @param FormView $view
     * @return array
     */
    private function buildTemplateView(FormView $view): array
    {
        $vars = [];
        foreach ($view->children as $name=>$child) {
            $vars['children'][$name] = $this->buildTemplateView($child);
        }

        $vars['type'] = $this->renderFormType($view->vars['block_prefixes']);

        $vars['value'] = $view->vars['value'];

        if (is_object($view->vars['value']) && in_array(EntityInterface::class, class_implements($view->vars['value']))) {
            $vars['value'] = $view->vars['value']->getId();
        }

        if ($vars['type'] === 'choice' && $view->vars['multiple']) {
            if(!is_array($view->vars['value']))
                $view->vars['value'] = [$view->vars['value']];
            foreach($view->vars['value'] ?: [] as $q=>$w) {
                if (is_object($w) && in_array(EntityInterface::class, class_implements($w))) {
                    $view->vars['value'][$q] = $w->getId();
                }
            }
            $vars['value'] = $view->vars['value'];
        }

        if (in_array($vars['value'], [null,'',[]]) && !in_array($view->vars['data'], ['',null,[]])) {
            $vars['value'] = $view->vars['data'];
        }

        $vars['id'] = $view->vars['id'];
        $vars['name'] = $view->vars['name'];
        $vars['full_name'] = $view->vars['full_name'];
        $vars['disabled'] = $view->vars['disabled'];
        $vars['required'] = isset($view->vars['required']) ? $view->vars['required'] : false;
        $vars['on_change'] = $view->vars['on_change'];
        $vars['on_click'] = $view->vars['on_click'];
        $vars['on_blur'] = $view->vars['on_blur'];
        $vars['on_key_press'] = $view->vars['on_key_press'];
        $vars['submit_on_change'] = $view->vars['submit_on_change'];
        $vars['panel'] = $view->vars['panel'];
        $vars['row_style'] = $view->vars['row_style'];
        $vars['template'] = isset($view->vars['template']) ? $view->vars['template'] : null;
        $vars['panels'] = isset($view->vars['panels']) ?: 0;
        $vars['columns'] = isset($view->vars['columns']) ? $view->vars['columns'] : 0;
        $vars['multiple'] = isset($view->vars['multiple']) ? $view->vars['multiple'] : false;
        $vars['label_colspan'] = $view->vars['label_colspan'];
        $vars['widget_colspan'] = $view->vars['widget_colspan'];
        $vars['label_class'] = $view->vars['label_class'];
        $vars['widget_class'] = $view->vars['widget_class'];
        $vars['row_class'] = $view->vars['row_class'];
        $vars['column_attr'] = $view->vars['column_attr'];
        $vars['row_id'] = $view->vars['row_id'];
        $vars['wrapper_class'] = $view->vars['wrapper_class'];
        $vars['errors'] =  isset($view->vars['errors']) ? $this->renderErrors($view->vars['errors']) : [];
        if (isset($view->vars['visibleByClass'])) {
            $vars['visibleByClass'] = $view->vars['visibleByClass'];
            $vars['visibleWhen'] = $view->vars['visibleWhen'];
            $vars['values'] = $view->vars['values'];
        }
        if (in_array($vars['type'], ['collection','unknown'])) {
            $vars['value'] = null;
        }
        if (in_array($vars['type'], ['file'])) {
            $vars['delete_security'] = isset($view->vars['delete_security']) ? $view->vars['delete_security'] : false;
        }
        if (in_array($vars['type'], ['repeated'])) {
            $vars['row_style'] = 'transparent';
        }
        if ($vars['type'] === 'password_generator') {
            $vars['generateButton'] = $view->vars['generateButton'];
        }
        if (in_array($vars['type'], ['collection'])) {
            if (isset($view->vars['prototype']))
                $vars['prototype'] = $this->buildTemplateView($view->vars['prototype']);
            $vars['collection_key'] = uniqid('collection', true);
            $vars['header_row'] = $view->vars['header_row'];
            $vars['allow_delete'] = $view->vars['allow_delete'];
            $vars['allow_add'] = $view->vars['allow_add'];
            $vars['element_delete_route'] = $view->vars['element_delete_route'];
            $vars['element_delete_options'] = $view->vars['element_delete_options'];
        }

        if ($view->vars['translation_domain'] !== false) {
            if (!(null === $view->vars['label'] || false === $view->vars['label']))
                $vars['label'] = $this->translate($view->vars['label'], $view->vars['label_translation_parameters'], $this->getTranslationDomain($view->vars['translation_domain']));
            if (isset($view->vars['help']) && !(null === $view->vars['help'] || false === $view->vars['help']) && $view->vars['translation_domain'] !== false)
                $vars['help'] = $this->translate($view->vars['help'], $view->vars['help_translation_parameters'], $this->getTranslationDomain($view->vars['translation_domain']));
        } else {
            if (!(null === $view->vars['label'] || false === $view->vars['label']))
                $vars['label'] = $view->vars['label'];
            if (isset($view->vars['help']) && !(null === $view->vars['help'] || false === $view->vars['help']))
                $vars['help'] = $view->vars['help'];
        }

        foreach($view->vars['attr'] as $attrName=>$attr)
            if (in_array($attrName, ['title', 'placeholder']))
                $vars['attr'][$attrName] = $this->translate($view->vars['attr'][$attrName], $view->vars['attr_translation_parameters'], $this->getTranslationDomain($view->vars['translation_domain']));
            else
                $vars['attr'][$attrName] = $attr;

        if (isset($view->vars['placeholder']))
            $vars['placeholder'] = $view->vars['placeholder'] ? $this->translate($view->vars['placeholder'], [], $this->getTranslationDomain($view->vars['translation_domain'])) : false;

        if (isset($view->vars['choices'])) {
            if (false !== $view->vars['choice_translation_domain'])
                foreach($view->vars['choices'] as $q=>$choice) {
                    $choice->label = $this->translate($choice->label, [], $this->getTranslationDomain($view->vars['choice_translation_domain']));
                    if (isset($choice->choices)) {
                        foreach($choice->choices as $w) {
                            $w->label = $this->translate($w->label, [], $this->getTranslationDomain($view->vars['choice_translation_domain']));
                        }
                    }
                }

            $vars['choice_translation_domain'] = false;

            // json_encode will sort if the index is not in order, so some work to do.
            $result = [];
            foreach($view->vars['choices'] as $q=>$choice) {
                $z = [];
                if (isset($choice->choices)) {
                    foreach($choice->choices as $w) {
                        $z[] = $w;
                    }
                    $choice->choices = $z;
                    $result[$q] = $choice;
                } else {
                    $result[] = $choice;
                }
            }

            $vars['choices'] = $result;
        }

        if (in_array('submit', $view->vars['block_prefixes']))
        {
            if (isset($view->vars['attr']['help']))
                $vars['help'] = $this->translate($view->vars['attr']['help'], [], $this->getTranslationDomain($view->vars['translation_domain']));
            else
                $vars['help'] = $this->translate('* denotes a required field', [], 'messages');
            $vars['attr']['class'] = '';
            $vars['label'] = $this->translate(isset($view->vars['label']) ? $view->vars['label'] : 'Submit', [], $this->getTranslationDomain($view->vars['translation_domain']));
        }

        return $vars;
    }

    /**
     * renderFormType
     * @param array $prefixes
     * @return string
     */
    private function renderFormType(array $prefixes) {
    //    dump($prefixes);
        if (in_array('header', $prefixes))
            return 'header';
        if (in_array('date', $prefixes))
            return 'date';
        if (in_array('toggle', $prefixes))
            return 'toggle';
        if (in_array('paragraph', $prefixes))
            return 'paragraph';
        if (in_array('ckeditor', $prefixes))
            return 'ckeditor';
        if (in_array('image_display', $prefixes))
            return 'image_display';
        if (in_array('textarea', $prefixes))
            return 'textarea';
        if (in_array('url', $prefixes))
            return 'url';
        if (in_array('password_generator', $prefixes))
            return 'password_generator';
        if (in_array('password', $prefixes))
            return 'password';
        if (in_array('file', $prefixes))
            return 'file';
        if (in_array('email', $prefixes))
            return 'email';
        if (in_array('color', $prefixes))
            return 'color';
        if (in_array('text', $prefixes))
            return 'text';
        if (in_array('auto_suggest', $prefixes))
            return 'auto_suggest';
        if (in_array('choice', $prefixes))
            return 'choice';
        if (in_array('display', $prefixes))
            return 'display';
        if (in_array('hidden', $prefixes))
            return 'hidden';
        if (in_array('collection', $prefixes))
            return 'collection';
        if (in_array('submit', $prefixes))
            return 'submit';
        if (in_array('button', $prefixes))
            return 'button';
        if (in_array('integer', $prefixes))
            return 'integer';
        if (in_array('repeated', $prefixes))
            return 'transparent';
        if (in_array('react_sub_form', $prefixes))
            return 'transparent';

//        dump($prefixes);
        return 'unknown';
    }

    /**
     * renderErrors
     * @param array $errors
     */
    private function renderErrors(FormErrorIterator $errors) {
        $result = [];
        foreach($errors as $error)
        {
            $result[] = $error->getMessage();
        }
        return $result;
    }

    /**
     * addTranslation
     * @return ReactFormType
     */
    public function addTranslation(string $id, array $options = [], ?string $domain = null): ReactFormType
    {
        TranslationsHelper::addTranslation($id, $options, $this->getTranslationDomain($domain));
        return $this;
    }
}
