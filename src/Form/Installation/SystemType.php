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
use App\Entity\Setting;
use App\Form\Entity\SystemSettings;
use App\Form\Type\EnumType;
use App\Form\Type\HeaderType;
use App\Provider\ProviderFactory;
use App\Validator\Directory;
use App\Validator\Enum;
use App\Validator\Password;
use App\Validator\UsernameEmail;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CurrencyType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimezoneType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Intl\Countries;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Url;

class SystemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $person = new Person();
        $provider = ProviderFactory::create(Setting::class);
        $baseUrl = $provider->getSettingByScope('System', 'absoluteURL', true);
        $basePath = $provider->getSettingByScope('System', 'absolutePath', true);
        $systemName = $provider->getSettingByScope('System', 'systemName', true);
        $installType = $provider->getSettingByScope('System', 'installType', true);
        $orgName = $provider->getSettingByScope('System', 'organisationName', true);
        $orgNameShort = $provider->getSettingByScope('System', 'organisationNameShort', true);
        $country = $provider->getSettingByScope('System', 'country', true);
        $countries = [];
        foreach (Countries::getNames()as $name) {
           $countries[$name] = $name;
        }
        $currency = $provider->getSettingByScope('System', 'currency', true);
        $timezone = $provider->getSettingByScope('System', 'timezone', true) ?: $options['timezone'];

        $builder
            ->add('userAccountHeader', HeaderType::class,
                [
                    'label' => 'User Account',
                ]
            )
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
            ->add('systemSettingsHeader', HeaderType::class,
                [
                    'label' => 'System Settings',
                ]
            )
            ->add('baseUrl', UrlType::class,
                [
                    'label' => $baseUrl->getNameDisplay(),
                    'help' => $baseUrl->getDescription(),
                    'attr' => [
                        'class' => 'w-full',
                        'maxLength' => 100,
                    ],
                    'constraints' => [
                        new NotBlank(),
                        new Url()
                    ],
                ]
            )
            ->add('basePath', TextType::class,
                [
                    'label' => $basePath->getNameDisplay(),
                    'help' => $basePath->getDescription(),
                    'attr' => [
                        'class' => 'w-full',
                        'maxLength' => 100,
                    ],
                    'constraints' => [
                        new NotBlank(),
                        new Directory(),
                    ],
                ]
            )
            ->add('systemName', TextType::class,
                [
                    'label' => $systemName->getNameDisplay(),
                    'help' => $systemName->getDescription(),
                    'attr' => [
                        'class' => 'w-full',
                        'maxLength' => 50,
                    ],
                    'constraints' => [
                        new NotBlank(),
                    ],
                ]
            )
            ->add('installType', EnumType::class,
                [
                    'label' => $installType->getNameDisplay(),
                    'help' => $installType->getDescription(),
                    'attr' => [
                        'class' => 'w-full',
                        'maxLength' => 50,
                    ],
                    'translation_domain' => 'kookaburra',
                    'constraints' => [
                        new NotBlank(),
                    ],
                    'choice_list_prefix' => false,
                ]
            )
            ->add('submit', SubmitType::class,
               [
                   'label' => 'Submit',
               ]
            )
            ->add('organisationHeader', HeaderType::class,
                [
                    'label' => 'Organisation Settings',
                ]
            )
            ->add('organisationName', TextType::class,
                [
                    'label' => $orgName->getNameDisplay(),
                    'help' => $orgName->getDescription(),
                    'attr' => [
                        'class' => 'w-full',
                        'maxLength' => 50,
                    ],
                    'constraints' => [
                        new NotBlank(),
                    ],
                ]
            )
            ->add('organisationNameShort', TextType::class,
                [
                    'label' => $orgNameShort->getNameDisplay(),
                    'help' => $orgNameShort->getDescription(),
                    'attr' => [
                        'class' => 'w-full',
                        'maxLength' => 10,
                    ],
                    'constraints' => [
                        new NotBlank(),
                    ],
                ]
            )
            ->add('miscellaneousHeader', HeaderType::class,
                [
                    'label' => 'Miscellaneous',
                ]
            )
            ->add('country', ChoiceType::class,
                [
                    'label' => $country->getNameDisplay(),
                    'help' => $country->getDescription(),
                    'placeholder' => '',
                    'choices' => $countries,
                    'attr' => [
                        'class' => 'w-full',
                    ],
                    'constraints' => [
                        new NotBlank(),
                    ],
                ]
            )
            ->add('currency', CurrencyType::class,
                [
                    'label' => $currency->getNameDisplay(),
                    'help' => $currency->getDescription(),
                    'placeholder' => '',
                    'attr' => [
                        'class' => 'w-full',
                    ],
                    'constraints' => [
                        new NotBlank(),
                    ],
                ]
            )
            ->add('timezone', TimezoneType::class,
                [
                    'label' => $timezone->getNameDisplay(),
                    'help' => $timezone->getDescription(),
                    'placeholder' => '',
                    'attr' => [
                        'class' => 'w-full',
                    ],
                    'constraints' => [
                        new NotBlank(),
                    ],
                ]
            )
        ;

    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'translation_domain' => 'messages',
                'data_class' => SystemSettings::class,
            ]
        );
        $resolver->setRequired(
            [
                'timezone',
            ]
        );
    }
}