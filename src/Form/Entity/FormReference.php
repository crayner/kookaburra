<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 14/08/2019
 * Time: 11:02
 */

namespace App\Form\Entity;

use Symfony\Component\Form\FormView;

/**
 * Class FormReference
 * @package App\Form\Entity
 */
class FormReference
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var FormView
     */
    private $formView;

    /**
     * @var bool
     */
    private $render = true;

    /**
     * @var string
     */
    private $renderStyle = 'row';

    /**
     * @var array
     */
    private static $renderStyleList = [
        'row',
        'widget',
        'errors',
        'label',
        'none',
    ];

    /**
     * FormReference constructor.
     */
    public function __construct(FormView $formView, string $name)
    {
        $this->formView = $formView;
        $this->setName($name);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Name.
     *
     * @param string $name
     * @return FormReference
     */
    public function setName(string $name): FormReference
    {
        $prototype = $this->formView->vars['prototype'];
        if (! isset($prototype->children[$name]))
           throw new \InvalidArgumentException(sprintf('The child "%s" is not a member of the  "%s" form. Ensure that the form has a child called "%s" and that the form has a prototype set.', $name, $this->formView->vars['full_name'], $name));
        $this->name = $name;
        return $this;
    }

    /**
     * toArray
     * @return array
     */
    public function toArray(): array
    {
        $x['name'] = $this->getName();
        $x['render'] = $this->isRender();
        $x['renderStyle'] = $this->getRenderStyle();
        return $x;
    }

    /**
     * @return bool
     */
    public function isRender(): bool
    {
        return $this->render;
    }

    /**
     * Render.
     *
     * @param bool $render
     * @return FormReference
     */
    public function setRender(bool $render): FormReference
    {
        $this->render = $render;
        if (!$render)
            return $this->setRenderStyle('none');
        return $this;
    }

    /**
     * @return string
     */
    public function getRenderStyle(): string
    {
        return $this->renderStyle = in_array($this->renderStyle, self::getRenderStyleList()) ? $this->renderStyle : 'row';
    }

    /**
     * RenderStyle.
     *
     * @param string $renderStyle
     * @return FormReference
     */
    public function setRenderStyle(string $renderStyle): FormReference
    {
        $this->renderStyle = in_array($renderStyle, self::getRenderStyleList()) ? $renderStyle : 'row';
        return $this;
    }

    /**
     * @return array
     */
    public static function getRenderStyleList(): array
    {
        return self::$renderStyleList;
    }
}