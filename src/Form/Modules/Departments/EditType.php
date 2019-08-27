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
use App\Form\EventSubscriber\FileOrLinkURLSubscriber;
use App\Form\Type\HeaderType;
use App\Form\Type\ReactCollectionType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class EditType
 * @package App\Form\Modules\Departments
 */
class EditType extends AbstractType
{
    /**
     * @var RequestStack
     */
    private $stack;

    /**
     * @var string
     */
    private $targetDir;

    /**
     * FileOrLinkURLSubscriber constructor.
     * @param RequestStack $stack
     * @param string $targetDir
     */
    public function __construct(RequestStack $stack, string $targetDir)
    {
        $this->stack = $stack;
        $this->targetDir = $targetDir;
    }

    /**
     * buildForm
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('overview', HeaderType::class,
                [
                    'label' => 'Overview',
                ]
            )
            ->add('blurb', CKEditorType::class,
                [
                    'label' => 'Description',
                    'required' => false,
                    'attr' => [
                        'rows' => 6,
                    ],
                    'row_merge' => [
                        'class' => 'flex flex-col sm:flex-row justify-between content-center p-0',
                        'style' => false,
                        'columns' => [
                            [
                                'class' => 'flex-grow justify-center px-2 border-b-0 sm:border-b border-t-0',
                                'style' => false,
                                'colspan' => 2,
                                'formElements' => [
                                    'widget',
                                    'errors',
                                ],
                                'wrapper' => [
                                    'class' => 'flex-1 relative',
                                ],
                            ],
                        ],
                    ],
                ]
            )
            ->add('resource_header', HeaderType::class,
                [
                    'label' => 'Resources',
                ]
            )
            ->add('resources', ReactCollectionType::class,
                [
                    'label' => false,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'entry_type' => ResourceType::class,
                    'prototype' => true,
                    'element_delete_route' => $options['resource_delete_route'],
                    'row_merge' => [
                        'thead' => [
                            'class' => '',
                            'columns' => [
                                [
                                    'class' => 'text-xxs sm:text-xs p-2 sm:py-3',
                                    'label' => 'Name',
                                ],
                                [
                                    'class' => 'text-xxs sm:text-xs p-2 sm:py-3',
                                    'label' => 'Type',
                                ],
                                [
                                    'class' => 'text-xxs sm:text-xs p-2 sm:py-3',
                                    'label' => 'Resource Location',
                                ],
                                [
                                    'class' => 'shortWidth text-xxs sm:text-xs p-2 sm:py-3 textCenter',
                                    'label' => 'Actions',
                                ],
                            ]
                        ],
                    ],
                ]
            )
            ->add('submit', SubmitType::class)
        ;
        $builder->get('resources')->addEventSubscriber(new FileOrLinkURLSubscriber($this->stack, $this->targetDir));
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
                'use_react' => true,
                'basic_to_array' => true,
            ]
        );
        $resolver->setRequired([
            'resource_manager',
            'resource_delete_route',
        ]);
    }

    public function getBlockPrefix()
    {
        return 'department_edit';
    }
}