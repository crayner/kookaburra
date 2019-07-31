<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 30/07/2019
 * Time: 12:47
 */

namespace App\Form\Modules\RollGroups;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class DetailStudentSortType
 * @package App\Form\Modules\RollGroups
 */
class DetailStudentSortType extends AbstractType
{
    /**
     * buildForm
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('sortBy', ChoiceType::class,
                [
                    'choices' => [
                        'Roll Order' => 'rollOrder, surname, preferredName',
                        'Surname' => 'surname, preferredName',
                        'Preferred Name' => 'preferredName, surname',
                    ],
                    'attr' => [
                        'class' => 'w-full',
                        'onChange' => 'this.form.submit()'
                    ],
                    'label' => 'Sort By',
                    'help' => 'denotes a required field',
                ]
            )
            ->add('confidential', CheckboxType::class,
                [
                    'attr' => [
                        'onChange' => 'this.form.submit()'
                    ],
                    'label' => 'Show Confidential Data',
                    'required' => false,
                    'label_class' => false,
                ]
            )
        ;
    }

    /**
     * getBlockPrefix
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'detail_student_sort';
    }

    /**
     * configureOptions
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'translation_domain' => 'gibbon',
                'attr' => [
                    'class' => 'noIntBorder fullWidth',
                ],
            ]
        );
    }
}