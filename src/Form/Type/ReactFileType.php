<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 4/09/2019
 * Time: 13:12
 */

namespace App\Form\Type;


use App\Form\EventSubscriber\ReactFileListener;
use App\Form\Transform\ReactFileTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ReactFileType
 * @package App\Form\Type
 */
class ReactFileType extends AbstractType
{
    /**
     * @var RequestStack
     */
    private $stack;

    /**
     * ReactFileType constructor.
     * @param RequestStack $stack
     */
    public function __construct(RequestStack $stack)
    {
        $this->stack = $stack;
    }


    /**
     * getParent
     * @return string|null
     */
    public function getParent()
    {
        return FileType::class;
    }

    /**
     * buildForm
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addViewTransformer(new ReactFileTransformer())
            ->addEventSubscriber(new ReactFileListener($this->stack));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'compound'     => false,
                'multiple'     => false,
                'type'         => 'file',
                'delete_security' => false,
            ]
        );

        $resolver->setRequired(
            [
                'file_prefix',
            ]
        );

        $resolver->setAllowedTypes('delete_security', ['boolean', 'string']);
    }

    /**
     * buildView
     * @param FormView $view
     * @param FormInterface $form
     * @param array $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['public_dir'] = realpath(__DIR__ . '/../../../public');
        $view->vars['value'] = $options['data'];
        $view->vars['delete_security'] = $options['delete_security'];
    }
}