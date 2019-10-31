<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 7/08/2019
 * Time: 13:49
 */

namespace App\Form\Registration;

use App\Entity\Person;
use App\Form\Type\CustomFieldType;
use App\Form\Type\EnumType;
use App\Validator\Password;
use App\Validator\RegistrationMinimumAge;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class PublicType
 * @package App\Form\Registration
 */
class PublicType extends AbstractType
{
    /**
     * buildForm
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('surname', TextType::class,
                [
                    'label' => 'Surname',
                    'attr' => [
                        'class' => 'w-full',
                        'maxLength' => 60,
                    ],
                    'constraints' => [
                        new NotBlank(),
                    ]
                ]
            )
            ->add('firstName', TextType::class,
                [
                    'label' => 'First Name',
                    'attr' => [
                        'class' => 'w-full',
                        'maxLength' => 60,
                    ],
                    'constraints' => [
                        new NotBlank(),
                    ]
                ]
            )
            ->add('email', EmailType::class,
                [
                    'label' => 'Email',
                    'attr' => [
                        'class' => 'w-full',
                        'maxLength' => 75,
                    ],
                    'constraints' => [
                        new NotBlank(),
                        new Email(),
                    ]
                ]
            )
            ->add('gender', EnumType::class,
                [
                    'label' => 'Gender',
                    'attr' => [
                        'class' => 'w-full',
                    ],
                    'choice_list_prefix' => false,
                    'placeholder' => 'Please select...',
                    'constraints' => [
                        new NotBlank(),
                    ]
               ]
            )
            ->add('dob', BirthdayType::class,
                [
                    'label' => 'Date of Birth',
                    'help' => 'date_format',
                    'help_translation_parameters' => ['%format%' => $options['dateFormat']],
                    'attr' => [
                        'class' => 'w-full',
                    ],
                    'constraints' => [
                        new NotBlank(),
                        new RegistrationMinimumAge(),
                    ]
                ]
            )
            ->add('username', TextType::class,
                [
                    'label' => 'Username',
                    'help' => 'Must be unique',
                    'attr' => [
                        'class' => 'w-full',
                        'maxLength' => 20,
                    ],
                    'constraints' => [
                        new NotBlank(),
                    ]
                ]
            )
            ->add('passwordNew', TextType::class,
                [
                    'label' => 'Password',
                    'mapped' => false,
                    'attr' => [
                        'class' => 'w-full',
                        'maxLength' => 30,
                    ],
                    'constraints' => [
                        new NotBlank(),
                        new Password(),
                    ]
                ]
            )
            ->add('agreement', CheckboxType::class,
                [
                    'label' => 'Agreement',
                    'help' => 'Do you agree to the above?',
                    'mapped' => false,
                    'label_class' => false,
                    'sub_label' => 'Yes',
                    'constraints' => [
                        new NotBlank(['message' => 'You must check this agreement to register!']),
                    ]
                ]
            )
            ->add('fields', CollectionType::class,
                [
                    'label' => false,
                    'entry_type' => CustomFieldType::class,
                    'allow_add' => false,
                    'allow_delete' => false,
                    'entry_options' => [
                        'customFields' => $options['customFields'],
                    ],
                ]
            )
            ->add('submit', SubmitType::class,
                [
                    'label' => 'Submit',
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
                'translation_domain' => 'messages',
                'data_class' => Person::class,
                'constraints' => [
                    new UniqueEntity(['email']),
                ],
                'customFields' => [],
            ]
        );
        $resolver->setRequired(
            [
                'dateFormat',
            ]
        );
    }

    /**
     * buildView
     * @param FormView $view
     * @param FormInterface $form
     * @param array $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['customFields'] = $options['customFields'];
    }
}