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
 * Date: 27/07/2019
 * Time: 14:48
 */

namespace App\Twig;

/**
 * Class TableAction
 * @package App\Twig
 */
class TableAction
{
    /**
     * @var string|null
     */
    private $route;

    /**
     * @var string|null
     */
    private $style;

    /**
     * @var string|null
     */
    private $title;

    /**
     * @var string|null
     */
    private $actionClass = 'fa-grey p-3 sm:p-0';

    /**
     * @var string|null
     */
    private $iconClass = 'ml-1';

    /**
     * @var array
     */
    private $dataField = [];

    /**
     * @return string|null
     */
    public function getRoute(): ?string
    {
        return $this->route;
    }

    /**
     * Route.
     *
     * @param string|null $route
     * @return TableAction
     */
    public function setRoute(?string $route): TableAction
    {
        $this->route = $route;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getStyle(): ?string
    {
        return $this->style;
    }

    /**
     * Style.
     *
     * @param string|null $style
     * @return TableAction
     */
    public function setStyle(?string $style): TableAction
    {
        $this->style = $style;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Title.
     *
     * @param string|null $title
     * @return TableAction
     */
    public function setTitle(?string $title): TableAction
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getActionClass(): ?string
    {
        return $this->actionClass;
    }

    /**
     * ActionClass.
     *
     * @param string|null $actionClass
     * @return TableAction
     */
    public function setActionClass(?string $actionClass): TableAction
    {
        $this->actionClass = $actionClass;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getIconClass(): ?string
    {
        return $this->iconClass;
    }

    /**
     * IconClass.
     *
     * @param string|null $iconClass
     * @return TableAction
     */
    public function setIconClass(?string $iconClass): TableAction
    {
        $this->iconClass = $iconClass;
        return $this;
    }

    /**
     * @return array|null
     */
    public function getDataField(): array
    {
        return $this->dataField;
    }

    /**
     * DataField.
     *
     * @param array|null $dataField
     * @return TableAction
     */
    public function setDataField(array $dataField): TableAction
    {
        $this->dataField = $dataField;
        return $this;
    }

    /**
     * create
     * @param string $title
     * @param string $style
     * @param string $route
     * @param array $dataField
     * @return TableAction
     */
    public static function create(string $title, string $style, string $route, array $dataField): self
    {
        $action = new self();
        return $action->setTitle($title)->setStyle($style)->setRoute($route)->setDataField($dataField);
    }
}