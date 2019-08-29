<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 28/08/2019
 * Time: 13:59
 */

namespace App\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ReactSubFormExtension
 * @package App\Form\Extension
 */
class ReactSubFormExtension extends AbstractTypeExtension
{
    /**
     * getExtendedTypes
     * @return array|iterable
     */
    public static function getExtendedTypes()
    {
        return [
            ButtonType::class,
            FormType::class,
        ];
    }

    /**
     * configureOptions
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'on_change'     => false,
                'on_click'      => false,
                'panel'         => 1,
                'row_style'     => 'standard',
                'column_attr'   => false,
            ]
        );

        $resolver->setAllowedTypes('panel', ['integer']);
        $resolver->setAllowedTypes('on_click', ['boolean','string']);
        $resolver->setAllowedTypes('on_change', ['boolean','string']);
        $resolver->setAllowedTypes('column_attr', ['boolean','string']);

        $resolver->setAllowedValues('row_style', ['standard', 'single', 'header', 'collection_column', 'collection', 'hidden']);
    }

    /**
     * buildView
     * @param FormView $view
     * @param FormInterface $form
     * @param array $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['on_change'] = $options['on_change'];
        $view->vars['on_click'] = $options['on_click'];
        $view->vars['panel'] = $options['panel'];
        $view->vars['row_style'] = $options['row_style'];
        $view->vars['column_attr'] = $options['column_attr'];
    }
}