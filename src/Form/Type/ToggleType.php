<?php
namespace App\Form\Type;

use App\Form\Transform\ToggleTransformer;
use App\Manager\ScriptManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

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
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->addModelTransformer(new ToggleTransformer());
	}

    /**
     * buildView
     * @param FormView $view
     * @param FormInterface $form
     * @param array $options
     */
	public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $this->scriptManager->addToggleScript($view->vars['id']);
    }
}