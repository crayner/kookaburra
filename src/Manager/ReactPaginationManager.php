<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 14/09/2019
 * Time: 11:38
 */

namespace App\Manager;

use App\Entity\Setting;
use App\Manager\Entity\PaginationRow;
use App\Provider\ProviderFactory;
use App\Util\TranslationsHelper;
use App\Util\UrlGeneratorHelper;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;

/**
 * Class ReactPaginationManager
 * @package App\Manager
 */
abstract class ReactPaginationManager implements ReactPaginationInterface
{
    /**
     * @var integer
     */
    private $pageMax;

    /**
     * @var PaginationRow
     */
    private $row;

    /**
     * @var array
     */
    private $content;

    /**
     * @var ScriptManager
     */
    private $scriptManager;

    /**
     * @var string
     */
    private $targetElement = 'paginationContent';

    /**
     * ReactPaginationManager constructor.
     */
    public function __construct(ScriptManager $scriptManager)
    {
        $this->pageMax = ProviderFactory::create(Setting::class)->getSettingByScopeAsInteger('System', 'pagination');
        $this->scriptManager = $scriptManager;
    }

    /**
     * @return int
     */
    public function getPageMax(): int
    {
        return $this->pageMax;
    }

    /**
     * PageMax.
     *
     * @param int $pageMax
     * @return ReactPaginationManager
     */
    public function setPageMax(int $pageMax): ReactPaginationManager
    {
        $this->pageMax = $pageMax;
        return $this;
    }

    /**
     * @return PaginationRow
     */
    public function getRow(): PaginationRow
    {
        return $this->row = $this->row ?: new PaginationRow();
    }

    /**
     * Row.
     *
     * @param PaginationRow $row
     * @return ReactPaginationManager
     */
    public function setRow(PaginationRow $row): ReactPaginationManager
    {
        $this->row = $row;
        return $this;
    }

    /**
     * @return array
     */
    public function getContent(): array
    {
        return $this->content;
    }

    /**
     * Content.
     *
     * @param array $content
     * @return ReactPaginationManager
     */
    public function setContent(array $content): ReactPaginationManager
    {
        $this->content = $content;
        return $this->translateContent();
    }

    /**
     * translateContent
     * @return ReactPaginationManager
     * @throws InvalidOptionsException
     */
    private function translateContent(): ReactPaginationManager
    {
        $this->execute();
        foreach($this->getContent() as $q=>$content) {
            $this->content[$q] = gettype($content) === 'object' ? $content->toArray() : $content;
            foreach($this->getRow()->getActions() as $action)
            {
                $action->setTitle(TranslationsHelper::translate($action->getTitle()));
                $params = [];
                foreach($action->getRouteParams() as $name=>$contentName)
                {
                    if (gettype($content) === 'array' && isset($content[$contentName])) {
                        $params[$name] = $content[$contentName];
                    } else if (gettype($content) === 'object') {
                        $contentName = 'get' . ucfirst($contentName);
                        if (method_exists($content,$contentName))
                            $params[$name] = $content->$contentName();
                        else
                            throw new InvalidOptionsException(sprintf('The method %s was not found in %s ', $contentName, get_class($content)));
                    } else {
                        throw new InvalidOptionsException(sprintf('Not able to correctly collect the content %s ', $contentName));
                    }
                }
                $this->content[$q]['actions'][] = UrlGeneratorHelper::getPath($action->getRoute(), $params);
            }
        }
        $columns = new ArrayCollection();
        foreach($this->getRow()->getColumns() as $column)
        {
            if ($column->getContentType() === 'link') {
                $options = $column->getOptions();
                $ro = [];
                foreach((isset($options['route_options']) ? $options['route_options'] : []) as $q=>$w) {
                    $ro[$q] = '__'.$w.'__';
                }
                $options['link'] =  UrlGeneratorHelper::getPath($options['route'], $ro);
                $column->setOptions($options);
            }
            $column->setLabel(TranslationsHelper::translate($column->getLabel()));
            $columns->add($column->toArray());
        }
        $this->row->setColumns($columns);

        $actions = new ArrayCollection();
        foreach($this->getRow()->getActions() as $action)
        {
            $actions->add($action->toArray());
        }
        $this->row->setActions($actions);

        $filters = new ArrayCollection();
        foreach($this->row->getFilters() as $filter)
        {
            $filters->set($filter->getName(), $filter->toArray());
        }
        $this->row->setFilters($filters);

        return $this;
    }

    /**
     * toArray
     * @return array
     */
    final public function toArray(): array
    {
        return [
            'pageMax' => $this->getPageMax(),
            'row' => $this->getRow()->toArray(),
            'content' => $this->getContent(),
            'translations' => $this->getTranslations(),
            'targetElement' => $this->getTargetElement(),
        ];
    }

    /**
     * @return ScriptManager
     */
    public function getScriptManager(): ScriptManager
    {
        return $this->scriptManager;
    }

    /**
     * setPaginationScript
     * @return ReactPaginationManager
     */
    public function setPaginationScript(): ReactPaginationManager
    {
        $this->getScriptManager()->addAppProp('pagination', $this->toArray());
        return $this;
    }

    /**
     * getTranslations
     * @return array
     */
    public function getTranslations(): array
    {
        TranslationsHelper::addTranslation('Are you sure you want to delete this record?', [], 'messages');
        TranslationsHelper::addTranslation('This operation cannot be undone, and may lead to loss of vital data in your system. PROCEED WITH CAUTION!', [], 'messages');
        TranslationsHelper::addTranslation('Close', [], 'messages');
        TranslationsHelper::addTranslation('Yes', [], 'messages');
        TranslationsHelper::addTranslation('Filter', [], 'messages');
        TranslationsHelper::addTranslation('All', [], 'messages');
        TranslationsHelper::addTranslation('Clear', [], 'messages');
        TranslationsHelper::addTranslation('Search for', [], 'messages');
        TranslationsHelper::addTranslation('Filter Select', [], 'messages');
        return TranslationsHelper::getTranslations();
    }

    /**
     * @return string
     */
    public function getTargetElement(): string
    {
        return $this->targetElement;
    }

    /**
     * TargetElement.
     *
     * @param string $targetElement
     * @return ReactPaginationManager
     */
    public function setTargetElement(string $targetElement): ReactPaginationManager
    {
        $this->targetElement = $targetElement;
        return $this;
    }
}