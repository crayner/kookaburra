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
 * Date: 13/08/2019
 * Time: 12:54
 */

namespace App\Form;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

/**
 * Class ReactCollectionManager
 * @package App\Form
 */
class ReactCollectionManager
{
    /**
     * @var CollectionTemplateInterface
     */
    private $templateManager;

    /**
     * @var FormInterface
     */
    private $form;

    /**
     * renderForm
     * @param FormInterface $form
     * @param FormView $view
     * @param CollectionTemplateInterface $templateManager
     * @param string $templateName
     * @return array
     */
    public function renderForm(FormInterface $form, FormView $view, CollectionTemplateInterface $templateManager, string $templateName = 'template'): array
    {
        $template = $this
            ->setTemplateManager($templateManager)
            ->setForm($form)
            ->setFormView($view)
            ->loadTemplate($templateName);
        return [
            'template' => $template->toArray(),
        ];
    }

    /**
     * @return CollectionTemplateInterface
     */
    public function getTemplateManager(): CollectionTemplateInterface
    {
        return $this->templateManager;
    }

    /**
     * TemplateManager.
     *
     * @param CollectionTemplateInterface $templateManager
     * @return ReactCollectionManager
     */
    public function setTemplateManager(CollectionTemplateInterface $templateManager): ReactCollectionManager
    {
        $this->templateManager = $templateManager;
        return $this;
    }

    /**
     * validateTemplate
     * @param string $templateName
     */
    private function loadTemplate(string $templateName)
    {
        $name = 'get'.ucfirst($templateName);
        if (method_exists(get_class($this->getTemplateManager()), $name))
            return $this->getTemplateManager()->$name();
        throw new \InvalidArgumentException(sprintf('The template method called "%s" was not found in "%s"', $name, get_class($this->getTemplateManager())));
    }

    /**
     * @return FormInterface
     */
    public function getForm(): FormInterface
    {
        return $this->form;
    }

    /**
     * Form.
     *
     * @param FormInterface $form
     * @return ReactCollectionManager
     */
    public function setForm(FormInterface $form): ReactCollectionManager
    {
        $this->form = $form;
        return $this;
    }

    /**
     * setFormView
     * @param FormView $view
     * @return ReactCollectionManager
     */
    private function setFormView(FormView $view): ReactCollectionManager
    {
        $this->getTemplateManager()->setFormView($view);
        return $this;
    }
}
