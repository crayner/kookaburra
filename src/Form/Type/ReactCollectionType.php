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

use App\Form\EventSubscriber\ReactCollectionSubscriber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ReactCollectionType
 * @package App\Form\Type
 */
class ReactCollectionType extends AbstractType
{
    /**
     * configureOptions
     *
     * element_id_name  is used to inject a hidden form element based on the unique id
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(
            [
                'element_delete_route',
            ]
        );
        $resolver->setDefaults(
            [
                'row_style' => 'collection',
                'column_style' => 'collection',
                'basic_to_array' => true,
                'element_id_name' => 'id',
                'element_delete_options' => ['__id__' => 'id'],
            ]
        );
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
     * buildForm
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventSubscriber(new ReactCollectionSubscriber($options));
    }

    /**
     * buildView
     * @param FormView $view
     * @param FormInterface $form
     * @param array $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['allow_add'] = $options['allow_add'];
        $view->vars['allow_delete'] = $options['allow_delete'];
        $view->vars['element_delete_route'] = $options['element_delete_route'];
        $view->vars['element_delete_options'] = $options['element_delete_options'];
    }
}