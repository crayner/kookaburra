<?php
namespace App\Form\Type;

use App\Manager\ScriptManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ToggleType extends AbstractType
{
    /**
     * @var ScriptManager
     */
    private $scriptManager;

    /**
     * ToggleType constructor.
     * @param ScriptManager $scriptManager
     */
    public function __construct(ScriptManager $scriptManager)
    {
        $this->scriptManager = $scriptManager;
    }

    /**
     * @return null|string
     */
    public function getBlockPrefix()
    {
        return 'toggle';
    }

    /**
     * getParent
     * @return string|null
     */
    public function getParent()
    {
        return HiddenType::class;
    }

    /**
     * configureOptions
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
       $resolver->setDefaults(
           [
               'visibleByClass' => false,
               'visibleWhen' => 'Y',
               'values' => ['Y', 'N'],
           ]
       );
        $resolver->setAllowedTypes('visibleByClass', ['boolean', 'string']);
        $resolver->setAllowedTypes('visibleWhen', ['string']);
    }

    /**
     * buildView
     * @param FormView $view
     * @param FormInterface $form
     * @param array $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['visibleByClass'] = $options['visibleByClass'];
        $view->vars['visibleWhen'] = $options['visibleWhen'];
        $view->vars['values'] = $options['values'];
        $this->scriptManager->addPageScript('components/toggle_visibleByClass.html.twig', ['id' => $view->vars['id']]);
    }
}