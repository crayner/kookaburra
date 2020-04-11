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
 * Date: 24/07/2019
 * Time: 08:04
 */

namespace App\Form\Installation;

use App\Form\Type\HeaderType;
use App\Form\Type\ReactFormType;
use App\Manager\Entity\Language;
use Kookaburra\SystemAdmin\Entity\I18n;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\NotBlank;

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
        $builder
            ->add('titleBar', HeaderType::class,
                [
                    'label' => 'Language Setting'
                ]
            )
            ->add('code', ChoiceType::class,
                [
                    'choices' => I18n::getLanguages(),
                    'choice_translation_domain' => false,
                    'placeholder' => 'Please Select...',
                    'label' => 'System Language',
                    'required' => true,
                ]
            )->add('submit', SubmitType::class,
                [
                    'label' => 'Submit',
                ]
            )->setAction($this->router->generate('install__installation_check'));
    }

    /**
     * configureOptions
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Language::class,
                'translation_domain' => 'messages',
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