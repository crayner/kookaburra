<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 24/07/2019
 * Time: 09:41
 */

namespace App\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class TableTypeExtension
 * @package App\Form\Extension
 */
class TableTypeExtension extends AbstractTypeExtension
{
    /**
     * buildView
     *
     * @param FormView $view
     * @param FormInterface $form
     * @param array $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['label_colspan'] = $options['label_colspan'];
        $view->vars['widget_colspan'] = $options['widget_colspan'];
        $view->vars['label_class'] = $options['label_class'];
        $view->vars['widget_class'] = $options['widget_class'];
        $view->vars['row_class'] = $options['row_class'];
    }

    /**
     * configureOptions
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'label_colspan' => '1',
                'widget_colspan' => '1',
                'label_class' => null,
                'widget_class' => null,
                'row_class' => null,
                'help_attr' => [
                    'class' =>'text-xxs text-gray-600 italic font-normal mt-1 sm:mt-0',
                ],
            ]
        );
    }

    /**
     * getExtendedTypes
     * @return iterable
     */
    public static function getExtendedTypes(): iterable
    {
        return [
            FormType::class,
            SubmitType::class,
            ChoiceType::class,
        ];
    }

}