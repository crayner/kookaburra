<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 7/09/2019
 * Time: 11:57
 */

namespace App\Form\Modules\SystemAdmin;


use App\Form\Type\HeaderType;
use App\Form\Type\ReactFormType;
use App\Form\Type\SettingsType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class SMSSettingsType
 * @package App\Form\Modules\SystemAdmin
 */
class EmailSettingsType extends AbstractType
{
    /**
     * buildForm
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('emailHeader', HeaderType::class,
                [
                    'label' => 'E-Mail',
                ]
            )
            ->add('emailSettings', SettingsType::class,
                [
                    'settings' => [
                        [
                            'scope' => 'System',
                            'name' => 'enableMailerSMTP',
                            'entry_type' => ChoiceType::class,
                            'entry_options' => [
                                'choices' => [
                                    'GMail' => 'GMail', 'SMTP' => 'SMTP',
                                ],
                                'choice_translation_domain' => false,
                                'placeholder' => 'No',
                            ],
                        ],
                    ],
                ]
            )
            ->add('submit', SubmitType::class);
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
                'data_class' => null,
            ]
        );
    }

    /**
     * getParent
     * @return string|null
     */
    public function getParent()
    {
        return ReactFormType::class;
    }
}