<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 13/08/2019
 * Time: 10:45
 */

namespace App\Form\Type;

use App\Form\CollectionTemplateInterface;
use App\Form\ReactCollectionManager;
use App\Manager\ScriptManager;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;
use Twig\Markup;

/**
 * Class ReactCollectionType
 * @package App\Form\Type
 */
class ReactCollectionType extends AbstractType
{
    /**
     * @var ScriptManager
     */
    private $scriptManager;

    /**
     * @var ReactCollectionManager
     */
    private $manager;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var Environment
     */
    private $twig;

    /**
     * ReactCollectionType constructor.
     * @param ScriptManager $scriptManager
     */
    public function __construct(ScriptManager $scriptManager, ReactCollectionManager $manager, TranslatorInterface $translator, Environment $twig)
    {
        $this->scriptManager = $scriptManager;
        $this->manager = $manager;
        $this->translator = $translator;
        $this->twig = $twig;
    }

    /**
     * configureOptions
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired([
            'collection_manager',
            'collection_template',
        ]);
        $resolver->setDefaults([
            'template_name' => 'template',
            'collection_script_name' => 'collections',
        ]);
    }

    /**
     * getParent
     * @return string|null
     */
    public function getParent()
    {
        return CollectionType::class;
    }

    /**
     * getBlockPrefix
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'react_collection';
    }

    /**
     * @var FormInterface
     */
    private $form;

    /**
     * @var CollectionTemplateInterface
     */
    private $templateManager;

    /**
     * buildView
     * @param FormView $view
     * @param FormInterface $form
     * @param array $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $props = $this->scriptManager->getAppProps();
        $this->form = $form;
        $this->templateManager = $options['collection_manager'];
        $existing = $props->containsKey('collection') ? $props->get('collection') : [];
        $collection['target'] = 'collection_' . $view->vars['id'];
        $collection['params'] = $this->manager->renderForm($form, $view, $options['collection_manager'], $options['template_name']);
        $collection['params']['form'] =  $this->extractForm($view);
        $existing[] = $collection;

        $this->scriptManager->addAppProp($options['collection_script_name'], $existing);
    }

    /**
     * extractForm
     *
     * @param FormView|FormInterface $formView
     * @return array
     */
    public function extractForm($formView, $prototype = false): array
    {
        if ($formView instanceof FormInterface)
        {
            $this->form = $formView;
            $formView = $this->form->createView();
        }
        if (! $formView instanceof FormView)
            trigger_error(sprintf('Argument 1 passed to %s() must be an instance of Symfony\Component\Form\FormView or Symfony\Component\Form\Form, instance of %s given.', __METHOD__, get_class($formView)), E_USER_ERROR);

        $vars = $formView->vars;
        $vars['children'] = [];
        foreach($formView->children as $child)
        {
            $vars['children'][] = $this->extractForm($child);
        }

        if (is_object($vars['value'])){
            $vars['data_id'] = null;
            $vars['data_toString'] = null;
            if (method_exists($vars['value'], 'getId'))
                $vars['data_id'] = $vars['value']->getId();
            if (method_exists($vars['value'], 'getName'))
                $vars['data_toString'] = $vars['value']->getName();
            if (method_exists($vars['value'], '__toString'))
                $vars['data_toString'] = $vars['value']->__toString();
        }
        if (isset($vars['prototype']) && $vars['prototype'] instanceof FormView)
        {
            $vars['prototype'] = $this->extractForm($vars['prototype'], true);
        }

        if ($vars['required'])
            $vars['required'] = $this->getTranslator()->trans('form.required', [], 'FormTheme');
        else
            $vars['required'] = '';

        if (! empty($vars['label']))
            $vars['label'] = $this->getTranslator()->trans($vars['label'], [], $vars['translation_domain']);
        else
            $vars['label'] = '';

        if (! empty($vars['placeholder']))
            $vars['placeholder'] = $this->getTranslator()->trans($vars['placeholder'], [], $vars['translation_domain']);

        if (! empty($vars['help']))
            $vars['help'] = $this->getTranslator()->trans($vars['help'], $vars['help_params'], $vars['translation_domain']);
        else
            $vars['help'] = '';

        if (isset($vars['choices'])) {
            $x = $this->getFormInterface($this->form, $vars['id']);
            if (empty($vars['value'])) {
                if (empty($vars['value']) && ! empty($x->getViewData()))
                    $vars['value'] = $x->getViewData();
                if (empty($vars['value']) && ! empty($x->getNormData()))
                    $vars['value'] = $x->getNormData();
                if (empty($vars['value']) && ! empty($x->getData()))
                    $vars['value'] = $x->getData();
                $vars['data'] = $vars['value'];
            }
            $vars['choices'] = $this->translateChoices($vars);
            if (empty($vars['value']) && ! empty($vars['placeholder']))
                $vars['value'] = $vars['data'] = '';
            else if (empty($vars['value']) && ! empty($vars['choices'][0]) && ! $vars['multiple'])
                $vars['value'] = $vars['data'] = $vars['choices'][0]->value;
            if ($vars['multiple'] && $vars['value'] instanceof Collection)
                $vars['value'] = $vars['value']->toArray();

            if (! empty($x->getConfig()->getOption('choice_attr')))
                foreach($vars['choices'] as $choice)
                    $choice->attr = $x->getConfig()->getOption('choice_attr');
            if ($vars['expanded'])
                $vars['children'] = [];
        }
        if ($vars['errors']->count() > 0) {
            $errors = [];
            foreach($vars['errors'] as $error)
                $errors[] = $error->getMessage();
            $vars['errors'] = $errors;
        } else
            $vars['errors'] = [];

        unset($vars['form']);

        if (! $prototype)
            $vars['constraints'] = $this->extractConstraints($vars);
        else
            $vars['constraints'] = [];

        if ($vars['value'] instanceof Collection)
            $vars['value'] = $vars['value']->toArray();

        if (is_array($vars['value']))
            foreach($vars['value'] as $q=>$w)
                if (is_object($w))
                    $vars['value'][$q] = $w->toArray();

        if ($vars['data'] instanceof Collection)
            $vars['data'] = $vars['data']->toArray();
        
        if (is_array($vars['data']))
            foreach($vars['data'] as $q=>$w)
                if (is_object($w))
                    $vars['data'][$q] = $w->toArray();


        return $vars;
    }

    /**
     * @return TranslatorInterface
     */
    private function getTranslator(): TranslatorInterface
    {
        return $this->translator;
    }

    /**
     * extractConstraints
     *
     * @param $vars
     * @return array
     */
    private function extractConstraints($vars): array
    {
        $form = $this->getFormInterface($this->form, $vars['id']);
        $result = [];
        $required = false;
        $constraints = $form->getConfig()->getOption('constraints');
        foreach($constraints as $q=>$constraint)
        {
            $result[$q] = (array) $constraint;
            $name = explode('\\',get_class($constraint));
            $result[$q]['class'] = end($name);

            switch($result[$q]['class']) {
                case 'NotBlank':
                    $result[$q]['message'] = $this->getTranslator()->trans($result[$q]['message'], [], 'validators');
                    $required = true;
                    break;
                case 'Colour':
                    $result[$q]['message'] = $this->getTranslator()->trans($result[$q]['message'], [], 'validators');
                    break;
                case 'Length':
                    $result[$q]['maxMessage'] = $this->getTranslator()->transChoice($result[$q]['maxMessage'], $result[$q]['max'], ['{{ limit }}' => $result[$q]['max']], 'validators');
                    $result[$q]['minMessage'] = $this->getTranslator()->transChoice($result[$q]['minMessage'], $result[$q]['min'], ['{{ limit }}' => $result[$q]['min']], 'validators');
                    $result[$q]['exactMessage'] = $this->getTranslator()->transChoice($result[$q]['exactMessage'], $result[$q]['min'], ['{{ limit }}' => $result[$q]['max']], 'validators');
                    break;
                case 'Choice':
                    $result[$q]['message'] = $this->getTranslator()->trans($result[$q]['message'], [], 'validators');
                    $result[$q]['multipleMessage'] = $this->getTranslator()->trans($result[$q]['multipleMessage'], [], 'validators');
                    $result[$q]['maxMessage'] = $this->getTranslator()->transChoice($result[$q]['maxMessage'], $result[$q]['max'], ['{{ limit }}' => $result[$q]['max']], 'validators');
                    $result[$q]['minMessage'] = $this->getTranslator()->transChoice($result[$q]['minMessage'], $result[$q]['min'], ['{{ limit }}' => $result[$q]['min']], 'validators');
                    break;
                default:
                    dump($result[$q]);
                    trigger_error(sprintf('The constraint (%s) has no handler in the React Form Manager', $result[$q]['class']), E_USER_ERROR);
            }

        }
        if (! empty($vars['required']) && ! $required)
        {
            $notBlank['message'] = $this->getTranslator()->trans('The value should not be empty!', [], 'validators');
            $notBlank['class'] = 'NotBlank';
            $result[] = $notBlank;
        }
        return $result;
    }

    /**
     * getFormInterface
     *
     * @param FormInterface $form
     * @param $id
     * @return FormInterface
     */
    private function getFormInterface(FormInterface $form, $id): FormInterface
    {
        $name = $form->getName();
        if ($id === $name)
            return $form;
        if (mb_strpos($id, $name.'_') === 0) {
            $id = mb_substr($id, mb_strlen($name . '_'));
            foreach ($form->all() as $name => $child) {
                if ($id === $name)
                    return $child;
                if (mb_strpos($id, $name.'_') === 0)
                    return $this->getFormInterface($child, $id);
            }
        }
        return $form;
    }

    /**
     * translateChoices
     *
     * @param array $vars
     * @return array
     */
    private function translateChoices(array $vars): array
    {
        $domain = $vars['choice_translation_domain'];
        if ($domain === false)
            return $vars['choices'];
        if (empty($domain))
            $domain = $vars['translation_domain'];
        if ($domain === false)
            return $vars['choices'];

        foreach($vars['choices'] as $choice)
        {
            if (is_object($choice->data))
                return $vars['choices'];
            $choice->label = $this->getTranslator()->trans($choice->label, [], $domain);
        }
        return $vars['choices'];
    }

    /**
     * @return Environment
     */
    public function getTwig(): Environment
    {
        return $this->twig;
    }
}