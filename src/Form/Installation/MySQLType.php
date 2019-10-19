<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 22/07/2019
 * Time: 15:45
 */

namespace App\Form\Installation;

use App\Form\Entity\MySQLSettings;
use App\Validator\MySqlConnection;
use Doctrine\DBAL\Driver\PDOMySql\Driver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class MySQLType
 * @package App\Form\Installation
 */
class MySQLType extends AbstractType
{
    /**
     * buildForm
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('host', TextType::class,
                [
                    'label' => 'Database Server',
                    'help' => 'Localhost, IP address or domain.',
                    'attr' => [
                        'class' => 'w-full',
                        'maxLength' => 255,
                    ],
                    'widget_class' => 'w-full max-w-full sm:max-w-xs flex justify-end items-center px-2 border-b-0 sm:border-b border-t-0',
                    'constraints' => [
                        new NotBlank(),
                    ],
                ]
            )
            ->add('dbname', TextType::class,
                [
                    'label' => 'Database Name',
                    'help' => 'This database will be created if it does not already exist. Collation should be utf8_general_ci.',
                    'attr' => [
                        'class' => 'w-full',
                        'maxLength' => 50,
                    ],
                    'widget_class' => 'w-full max-w-full sm:max-w-xs flex justify-end items-center px-2 border-b-0 sm:border-b border-t-0',
                    'constraints' => [
                        new NotBlank(),
                    ],
                ]
            )
            ->add('user', TextType::class,
                [
                    'label' => 'Database Username',
                    'attr' => [
                        'class' => 'w-full',
                        'maxLength' => 50,
                    ],
                    'constraints' => [
                        new NotBlank(),
                    ],
                ]
            )
            ->add('password', TextType::class,
                [
                    'label' => 'Database Password',
                    'attr' => [
                        'class' => 'w-full',
                        'maxLength' => 50,
                    ],
                    'constraints' => [
                        new NotBlank(),
                    ],
                ]
            )
            ->add('port', TextType::class,
                [
                    'label' => 'Database Port',
                    'help' => 'The standard port for MySQL is 3306. Only change this if the MySQL Server is listening on a different port.',
                    'attr' => [
                        'class' => 'w-full',
                        'maxLength' => 5,
                    ],
                    'constraints' => [
                        new NotBlank(),
                    ],
                    'translation_domain' => 'messages',
                ]
            )
            ->add('demo', ChoiceType::class,
                [
                    'label' => 'Install Demo Data?',
                    'choices' => [
                        'No' => 'N',
                        'Yes' => 'Y',
                    ],
                    'attr' => [
                        'class' => 'w-full',
                        'maxLength' => 50,
                    ],
                    'constraints' => [
                        new NotBlank(),
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
                'data_class' => MySQLSettings::class,
                'translation_domain' => 'messages',
                'constraints' => [
                    new MySqlConnection(),
                ]
            ]
        );
    }
}