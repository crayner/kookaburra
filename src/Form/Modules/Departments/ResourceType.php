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
use App\Form\Type\FilePathType;
use App\Form\Type\HiddenEntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
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
                    'attr' => [
                        'class' => 'w-full',
                        'data-value' => 'name',
                        'onChange' => 'onChange(e)',
                    ],
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
                    'attr' => [
                        'class' => 'w-full',
                        'onChange' => 'onChange',
                    ],
                    'choice_attr' => [
                        'Link' => ['data-choice' => 'type.Link'],
                        'File' => ['data-choice' => 'type.File'],
                    ],
                ]
            )
            ->add('urlLink', UrlType::class,
                [
                    'label' => 'Resource Location',
                    'attr' => [
                        'class' => 'w-full',
                        'data-value' => 'url',
                        'onChange' => 'onChange(e)',
                    ],
                    'row_id' => 'link__name__'
                ]
            )
/*            ->add('urlFile', FilePathType::class,
                [
                    'label' => 'Resource Location',
                    'attr' => [
                        'class' => 'w-full',
                        'data-value' => 'url',
                        'onChange' => 'onChange(e)',
                    ],
                    'row_id' => 'file__name__',
                    'fileName' => 'resource_',
                ]
            )
*/            ->add('department', HiddenEntityType::class,
                [
                    'class' => Department::class,
                    'attr' => [
                        'data-value' => 'department',
                        'onChange' => 'onChange(e)',
                    ],
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => DepartmentResource::class,
                'translation_domain' => 'gibbon',
            ]
        );
    }
}