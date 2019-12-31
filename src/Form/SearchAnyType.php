<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * User: craig
 * Date: 5/11/2019
 * Time: 06:07
 */

namespace App\Form;

use App\Form\Entity\SearchAny;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class SearchAnyType
 * @package App\Form
 */
class SearchAnyType extends AbstractType
{
    /**
     * buildForm
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('search', TextType::class,
                [
                    'label' => 'Search for',
                    'required' => false,
                    'help' => "Searches all available fields to find a match.",
                ]
            )
            ->add('clear', SubmitType::class,
                [
                    'label' => '<span class="fas fa-broom fa-fw"></span>',
                    'attr' => [
                        'style' => 'float: right;',
                        'title' => 'Clear Search',
                        'class' => 'btn-gibbon',
                    ],
                ]
            )
            ->add('submit', SubmitType::class,
                [
                    'label' => '<span class="fas fa-search fa-fw"></span>',
                    'attr' => [
                        'style' => 'float: right;',
                        'title' => 'Search',
                        'class' => 'btn-gibbon',
                    ],
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
                'data_class' => SearchAny::class,
                'translation_domain' => 'messages',
            ]
        );
    }
}