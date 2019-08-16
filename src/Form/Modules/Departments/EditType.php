<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 13/08/2019
 * Time: 09:21
 */

namespace App\Form\Modules\Departments;

use App\Entity\Department;
use App\Form\Type\ReactCollectionType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class EditType
 * @package App\Form\Modules\Departments
 */
class EditType extends AbstractType
{
    /**
     * buildForm
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('blurb', CKEditorType::class,
                [
                    'label' => 'Description',
                    'required' => false,
                ]
            )
            ->add('resources', ReactCollectionType::class,
                [
                    'label' => false,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'entry_type' => ResourceType::class,
                    'prototype' => true,
                    'collection_manager' => $options['resource_manager'],
                    'collection_template' => 'modules/departments/resource_element.html.twig',
                ]
            )
            ->add('submit', SubmitType::class)
        ;
    }

    /**
     * configureOptions
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Department::class,
                'translation_domain' => 'gibbon',
            ]
        );
        $resolver->setRequired([
            'resource_manager',
        ]);
    }
}