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
use App\Form\Type\EnumType;
use App\Form\Type\HeaderType;
use App\Provider\ProviderFactory;
use App\Validator\Directory;
use App\Validator\Enum;
use App\Validator\Password;
use Kookaburra\SystemAdmin\Form\Entity\SystemSettings;
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
                   'choice_translation_domain' => 'messages',
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
                    'label' => $baseUrl ? $baseUrl->getNameDisplay() : 'Base URL',
                    'help' =>  $baseUrl ? $baseUrl->getDescription() : 'The address at which the whole system resides.',
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
                    'label' => $basePath ? $basePath->getNameDisplay() : 'Base Path',
                    'help' => $basePath ? $basePath->getDescription() : 'The local FS path to the system',
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
                    'label' => $systemName ? $systemName->getNameDisplay() : 'System Name',
                    'help' => $systemName ? $systemName->getDescription() : '',
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
                    'label' => $installType ? $installType->getNameDisplay() : 'Install Type',
                    'help' => $installType ? $installType->getDescription() : 'The purpose of this installation of Kookaburra',
                    'attr' => [
                        'class' => 'w-full',
                        'maxLength' => 50,
                    ],
                    'translation_domain' => 'messages',
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
                    'label' => $orgName ? $orgName->getNameDisplay() : 'Organisation Name',
                    'help' => $orgName ? $orgName->getDescription() : '',
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
                    'label' => $orgNameShort ? $orgNameShort->getNameDisplay() : 'Organisation Initials',
                    'help' => $orgNameShort ? $orgNameShort->getDescription() : '',
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
                    'label' => $country ? $country->getNameDisplay() : 'Country',
                    'help' => $country ? $country->getDescription() : 'The country the school is located in',
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
                    'label' => $currency ? $currency->getNameDisplay() : 'Currency',
                    'help' => $currency ? $currency->getDescription() : 'System-wde currency for financial transactions. Support for online payment in this currency depends on your credit card gateway: please consult their support documentation.',
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
                    'label' => $timezone ? $timezone->getNameDisplay() : 'Timezone',
                    'help' => $timezone ? $timezone->getDescription() : 'The timezone where the school is located',
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