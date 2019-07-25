<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 25/07/2019
 * Time: 09:39
 */

namespace App\Form\Installation;

use App\Entity\Person;
use App\Form\Entity\SystemSettings;
use App\Validator\Enum;
use App\Validator\Password;
use App\Validator\UsernameEmail;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class SystemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $person = new Person();
        $builder
            ->add('title', ChoiceType::class,
                [
                   'label' => 'Title',
                   'attr' => [
                       'class' => 'w-full',
                   ],
                   'placeholder' => '',
                   'required' => false,
                   'choice_translation_domain' => 'kookaburra',
                   'choices' => Person::getTitleList(true),
                   'constraints' => [
                       new Enum(['strict' => false, 'validList' => Person::getTitleList()]),
                   ],
                ]
            )
            ->add('surname', TextType::class,
                [
                    'label' => 'Surname',
                    'help' => 'Family name as shown in ID documents.',
                    'attr' => [
                        'class' => 'w-full',
                        'maxLength' => 60,
                    ],
                    'constraints' => [
                       new NotBlank(),
                    ],
                ]
            )
            ->add('firstName', TextType::class,
               [
                   'label' => 'First Name',
                   'help' => 'First name as shown in ID documents.',
                   'attr' => [
                       'class' => 'w-full',
                       'maxLength' => 60,
                   ],
                   'constraints' => [
                       new NotBlank(),
                   ],
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
                   ],
               ]
            )
            ->add('support', CheckboxType::class,
               [
                   'label' => 'Receive Support',
                   'help' => 'Join our mailing list and receive a welcome email from the team. @todo This is not yet implemented.',
                   'translation_domain' => 'kookaburra',
                   'value' => '1',
                   'sub_label' => 'Yes',
                   'required' => false,
               ]
            )
            ->add('username', TextType::class,
               [
                   'label' => 'Username',
                   'help' => 'Must be unique. System login name. Cannot be changed.',
                   'attr' => [
                       'class' => 'w-full',
                       'maxLength' => 20,
                   ],
                   'constraints' => [
                       new NotBlank(),
                   ],
               ]
            )
            ->add('password', RepeatedType::class,
               [
                   'first_options' => [
                       'label' => 'Password',
                       'attr' => [
                           'class' => 'w-full',
                           'maxLength' => 30,
                        ],
                   ],
                   'second_options' => [
                       'label' => 'Confirm Password',
                       'attr' => [
                           'class' => 'w-full',
                           'maxLength' => 30,
                       ],
                   ],
                   'type' => PasswordType::class,
                   'constraints' => [
                       new NotBlank(),
                       new Password(),
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
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'translation_domain' => 'gibbon',
                'data_class' => SystemSettings::class,
            ]
        );
    }
}