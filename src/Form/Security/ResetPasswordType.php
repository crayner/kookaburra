<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 19/08/2019
 * Time: 17:44
 */

namespace App\Form\Security;

use App\Entity\Setting;
use App\Form\Entity\ResetPassword;
use App\Form\Type\HeaderType;
use App\Form\Type\ParagraphType;
use App\Provider\ProviderFactory;
use App\Validator\CurrentPassword;
use App\Validator\Password;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ResetPasswordType
 * @package App\Form\Security
 */
class ResetPasswordType extends AbstractType
{
    /**
     * buildForm
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $provider = ProviderFactory::create(Setting::class);
        $builder
            ->add('resetPassword', HeaderType::class,
                [
                    'label' => 'Reset Password',
                ]
            )
            ->add('policy', ParagraphType::class,
                [
                    'help' => $options['policy'],
                    'wrapper' => [
                        'class' => 'warning',
                    ],
                ]
            )
            ->add('current', PasswordType::class,
                [
                    'label' => 'Current Password',
                    'attr' => [
                        'class' => 'w-full',
                    ],
                    'constraints' => [
                        new CurrentPassword()
                    ],
                ]
            )
            ->add('raw', GeneratePasswordType::class,
                [
                    'type' => PasswordType::class,
                    'first_options' => [
                        'label' => 'New Password',
                        'row_merge' => [
                            'title' => 'Generate',
                            'translate' => [
                                'Copy this password if required' => [],
                            ],
                            'button' => [
                                'class' => 'button generatePassword -ml-px button-right',
                            ],
                            'passwordPolicy' => [
                                'alpha' => $provider->getSettingByScopeAsBoolean('System', 'passwordPolicyAlpha'),
                                'numeric' => $provider->getSettingByScopeAsBoolean('System', 'passwordPolicyNumeric'),
                                'punctuation' => $provider->getSettingByScopeAsBoolean('System', 'passwordPolicyNonAlphaNumeric'),
                                "minLength" => $provider->getSettingByScopeAsInteger('System', 'passwordPolicyMinLength'),
                            ],
                        ],
                    ],
                    'second_options' => [
                        'label' => 'Confirm New Password',
                    ],
                    'constraints' => [
                        new Password(),
                    ],
                    'invalid_message' => 'Your request failed due to non-matching passwords.',
                ]
            )
            ->add('submit', SubmitType::class)
            ->setAction($options['action'])
        ;
    }

    /**
     * configureOptions
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(
            [
                'action',
                'policy',
            ]
        );
        $resolver->setDefaults(
            [
                'data_class' => ResetPassword::class,
                'translation_domain' => 'gibbon',
                'use_react' => true,
            ]
        );
    }
}