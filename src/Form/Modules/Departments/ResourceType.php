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
                        'onChange' => true,
                    ],
                    'choice_attr' => [
                        'Link' => ['dataChoice' => 'type.Link'],
                        'File' => ['dataChoice' => 'type.File'],
                    ],
                ]
            )
            ->add('urlLink', UrlType::class,
                [
                    'label' => 'Resource Location',
                    'attr' => [
                        'dataValue' => 'url',
                    ],
                    'row_class' => 'flex flex-col sm:flex-row justify-between content-center p-0 link__name__ hidden',
                ]
            )
            ->add('urlFile', FilePathType::class,
                [
                    'label' => 'Resource Location',
                    'attr' => [
                        'dataValue' => 'url',
                    ],
                    'fileName' => 'resource_',
                    'row_class' => 'flex flex-col sm:flex-row justify-between content-center p-0 file__name__ hidden',
                ]
            )
            ->add('department', HiddenEntityType::class,
                [
                    'class' => Department::class,
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