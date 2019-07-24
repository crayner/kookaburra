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
        $view->vars['colspan_label'] = $options['colspan_label'];
        $view->vars['colspan_widget'] = $options['colspan_widget'];
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
                'colspan_label' => '1',
                'colspan_widget' => '1',
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