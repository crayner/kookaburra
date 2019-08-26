<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 13/08/2019
 * Time: 10:37
 */

namespace App\Form\Modules\Departments;

use App\Entity\Department;
use App\Entity\DepartmentResource;
use App\Form\Type\HiddenEntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ResourceType
 * @package App\Form\Modules\Departments
 */
class ResourceType extends AbstractType
{
    /**
     * buildForm
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class,
                [
                    'label' => 'Resource Name',
                ]
            )
            ->add('type', ChoiceType::class,
                [
                    'label' => 'Resource Type',
                    'choices' => [
                        'Link' => 'Link',
                        'File' => 'File',
                    ],
                    'empty_data' => 'Link',
                    'on_change' => 'manageLinkOrFile'
                ]
            )
            ->add('url', FileURLType::class,
                [
                    'label' => 'Resource Location',
                    'file_prefix' => 'resource',
                    'row_merge' => [
                        'button' => [
                            'class' => 'button -ml-px button-right',
                        ],
                        'security' => 'departments__edit',
                        'title' => 'Open Resource',
                    ],
                ]
            )
            ->add('department', HiddenEntityType::class,
                [
                    'class' => Department::class,
                ]
            )
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
                'data_class' => DepartmentResource::class,
                'translation_domain' => 'gibbon',
                'basic_to_array' => true,
            ]
        );
    }
}