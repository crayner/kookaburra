<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 20/09/2019
 * Time: 09:37
 */

namespace App\Form\Modules\SystemAdmin;

use App\Form\Type\ToggleType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ImportStep2Type
 * @package App\Form\Modules\SystemAdmin
 */
class ImportStep2Type extends AbstractType
{
    /**
     * buildForm
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('modes', HiddenType::class)
            ->add('columnOrder', HiddenType::class)
            ->add('fieldDelimiter', HiddenType::class)
            ->add('stringEnclosure', HiddenType::class)
            ->add('ignoreErrors', HiddenType::class)
            ->add('syncField', ToggleType::class,
                [
                    'label' => 'Sync?',
                    'help' => 'Only rows with a matching database ID will be imported.',
                    'visibleByClass' => 'syncDetails',
                    'visibleWhen' => '1',
                    'wrapper_class' => 'flex-1 relative right',
                    'values' => ['1', '0'],
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
                'attr' => [
                    'class' => 'smallIntBorder fullWidth standardForm',
                    'autocomplete' => 'on',
                    'enctype' => 'multipart/form-data',
                    'id' => 'importStep2',
                ],
                'translation_domain' => 'messages',
            ]
        );
        $resolver->setRequired(
            [
                'importReport',
            ]
        );
    }
}