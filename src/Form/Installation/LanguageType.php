<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 24/07/2019
 * Time: 08:04
 */

namespace App\Form\Installation;

use App\Entity\I18n;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class LanguageType
 * @package App\Form\Installation
 */
class LanguageType extends AbstractType
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * LanguageType constructor.
     * @param RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * buildForm
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('code', ChoiceType::class,
            [
                'choices' => array_flip($options['data']::getLanguages()),
                'choice_translation_domain' => false,
                'label' => 'System Language',
                'required' => true,
                'attr' => [
                    'class' => 'w-64',
                ],
                'widget_colspan' => '2',
                'widget_class' => 'x-2 border-b-0 sm:border-b border-t-0 right',
                'label_class' => 'px-2 border-b-0 sm:border-b border-t-0',
                'row_class' => false,
            ]
        )->add('submit', SubmitType::class,
            [
                'label' => 'Submit',
                'widget_colspan' => '2',
                'widget_class' => 'x-2 border-b-0 sm:border-b border-t-0 right',
                'label_class' => 'px-2 border-b-0 sm:border-b border-t-0',
                'row_class' => false,
            ]
        )->setAction($this->router->generate('installation_check'));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => I18n::class,
                'translation_domain' => 'gibbon',
            ]
        );
    }
}