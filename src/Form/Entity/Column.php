<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 14/08/2019
 * Time: 07:44
 */

namespace App\Form\Entity;

use ProxyManager\Generator\Util\UniqueIdentifierGenerator;

class Column
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $class;

    /**
     * @var integer
     */
    private $colspan = 1;

    /**
     * @var array
     */
    private $style = [];

    /**
     * @var FormReference|boolean
     */
    private $formReference = false;

    /**
     * @var string
     */
    private $label = '';

    /**
     * @var array
     */
    private $labelParams = [];

    /**
     * @var array
     */
    private $labelChoice = [];

    /**
     * @var string
     */
    private $labelWrapper = '';

    /**
     * Column constructor.
     * @param string $type
     */
    public function __construct(string $type = 'table')
    {
        $this->setClass('flex-grow justify-center px-2 border-b-0 sm:border-b border-t-0');
        if ('table' !== $type)
            $this->setClass('flex-grow');  // @todo Realistic div default class.
        $this->setId();
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Id.
     *
     * @param string $id
     * @return Column
     */
    public function setId(): Column
    {
        $this->id = UniqueIdentifierGenerator::getIdentifier('column');
        return $this;
    }

    /**
     * @return string
     */
    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * Class.
     *
     * @param string $class
     * @return Column
     */
    public function setClass(string $class): Column
    {
        $this->class = trim($class);
        return $this;
    }

    /**
     * mergeClass
     * @param string $class
     * @return Column
     */
    public function mergeClass(string $class): Column
    {
        if ('' === $class)
            return $this;
        $class .= ' '. $this->getClass();

        return $this->setClass($class);
    }

    /**
     * @return int
     */
    public function getColspan(): int
    {
        return $this->colspan;
    }

    /**
     * Colspan.
     *
     * @param int $colspan
     * @return Column
     */
    public function setColspan(int $colspan): Column
    {
        $this->colspan = $colspan;
        return $this;
    }

    /**
     * @return array
     */
    public function getStyle(): array
    {
        return $this->style;
    }

    /**
     * Style.
     *
     * @param array $style
     * @return Column
     */
    public function setStyle(array $style): Column
    {
        $this->style = $style;
        return $this;
    }

    /**
     * @return FormReference
     */
    public function getFormReference(): FormReference
    {
        return $this->formReference;
    }

    /**
     * FormReference.
     *
     * @param FormReference $formReference
     * @return Column
     */
    public function setFormReference(FormReference $formReference): Column
    {
        $this->formReference = $formReference;
        return $this;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * Label.
     *
     * Translated string to be displayed.
     * @param string $label
     * @return Column
     */
    public function setLabel(string $label): Column
    {
        $this->label = $label;
        return $this;
    }

    /**
     * @return array
     */
    public function getLabelParams(): array
    {
        return $this->labelParams;
    }

    /**
     * LabelParams.
     *
     * @param array $labelParams
     * @return Column
     */
    public function setLabelParams(array $labelParams): Column
    {
        $this->labelParams = $labelParams;
        return $this;
    }

    /**
     * @return array
     */
    public function getLabelChoice(): array
    {
        return $this->labelChoice;
    }

    /**
     * LabelChoice.
     *
     * @param array $labelChoice
     * @return Column
     */
    public function setLabelChoice(array $labelChoice): Column
    {
        $this->labelChoice = $labelChoice;
        return $this;
    }

    /**
     * toArray
     * @return array
     */
    public function toArray(): array
    {
        $column['style'] = $this->getStyle();
        $column['class'] = $this->getClass();
        $column['id'] = $this->getId();
        $column['label'] = $this->getLabel();
        $column['labelParams'] = $this->getLabelParams();
        $column['labelChoice'] = $this->getLabelChoice();
        $column['labelWrapper'] = $this->getLabelWrapper();
        $column['formReference'] = $this->getFormReference() ? $this->getFormReference()->toArray() : null;
        $column['buttons'] = false;
        return $column;
    }

    /**
     * @return string
     */
    public function getLabelWrapper(): string
    {
        return $this->labelWrapper;
    }

    /**
     * LabelWrapper.
     *
     * @param string $labelWrapper
     * @return Column
     */
    public function setLabelWrapper(string $labelWrapper): Column
    {
        $this->labelWrapper = $labelWrapper;
        return $this;
    }
}