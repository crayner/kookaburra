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
 * Date: 14/09/2019
 * Time: 11:38
 */

namespace App\Manager;

use App\Entity\Setting;
use App\Exception\MissingClassException;
use App\Manager\Entity\PaginationRow;
use App\Provider\ProviderFactory;
use App\Util\StringHelper;
use App\Util\TranslationsHelper;
use App\Util\UrlGeneratorHelper;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
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
     * @var string|bool
     */
    private $contentLoader = false;

    /**
     * @var array
     */
    private $initialFilter = [];

    /**
     * @var string
     */
    private $initialSearch = '';

    /**
     * @var RequestStack
     */
    private $stack;

    /**
     * @var string|null
     */
    private $storeFilterURL;

    /**
     * @var boolean
     */
    private $sortList = false;

    /**
     * @var boolean
     */
    private $draggableSort = false;

    /**
     * @var string
     */
    private $draggableRoute = '';

    /**
     * @var string
     */
    private $addElementRoute = '';

    /**
     * ReactPaginationManager constructor.
     * @param ScriptManager $scriptManager
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
        if (null === $this->pageMax)
            $this->pageMax = ProviderFactory::create(Setting::class)->getSettingByScopeAsInteger('System', 'pagination');
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
            'addElementRoute' => $this->getAddElementRoute(),
            'sortList' => $this->isSortList(),
            'draggableSort' => $this->isDraggableSort(),
            'draggableRoute' => $this->isDraggableSort() ? UrlGeneratorHelper::getPath($this->getDraggableRoute(), ['target' => '__target__', 'source' => '__source__']) : '',
            'content' => $this->getContent(),
            'contentLoader' => $this->getContentLoader(),
            'translations' => $this->getTranslations(),
            'targetElement' => $this->getTargetElement(),
            'storeFilterURL' => $this->getStoreFilterURL(),
            'initialFilter' => $this->getInitialFilter(),
            'initialSearch' => $this->getInitialSearch(),
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
        TranslationsHelper::addTranslation('There are no records to display.', [],'messages');
        TranslationsHelper::addTranslation('Loading Content...', [],'messages');
        TranslationsHelper::addTranslation('Default filtering is enforced.', [], 'messages');
        TranslationsHelper::addTranslation('Close Message', [], 'messages');
        TranslationsHelper::addTranslation('Items rows can be dragged into the correct position.', [], 'messages');
        TranslationsHelper::addTranslation('Loading', [], 'messages');
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

    /**
     * @return bool|string
     */
    public function getContentLoader()
    {
        return $this->contentLoader;
    }

    /**
     * ContentLoader.
     *
     * Url of the content loader
     * @param bool|string $contentLoader
     * @return ReactPaginationManager
     */
    public function setContentLoader(string $contentLoader)
    {
        $this->contentLoader = $contentLoader;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getStoreFilterURL(): ?string
    {
        return $this->storeFilterURL;
    }

    /**
     * StoreFilterURL.
     *
     * @param string|null $storeFilterURL
     * @return ReactPaginationManager
     */
    public function setStoreFilterURL(?string $storeFilterURL): ReactPaginationManager
    {
        $this->storeFilterURL = $storeFilterURL;
        $this->readInitialFilter();
        return $this;
    }

    /**
     * getStoredFilter
     * @return array
     */
    public function getInitialFilter(): array
    {
        return $this->initialFilter;
    }

    /**
     * InitialFilter.
     *
     * @param array $initialFilter
     * @return ReactPaginationManager
     */
    public function setInitialFilter(array $initialFilter): ReactPaginationManager
    {
        $this->initialFilter = $initialFilter;
        return $this;
    }

    /**
     * @return string
     */
    public function getInitialSearch(): string
    {
        return $this->initialSearch;
    }

    /**
     * InitialSearch.
     *
     * @param string $initialSearch
     * @return ReactPaginationManager
     */
    public function setInitialSearch(string $initialSearch): ReactPaginationManager
    {
        $this->initialSearch = $initialSearch;
        return $this;
    }

    /**
     * @return RequestStack
     */
    public function getStack(): RequestStack
    {
        if (null === $this->stack)
            trigger_error(sprintf('The request stack has not been injected into the class %s.  Use the calls function to setStack in the service configuration for this class.', get_class($this)), E_USER_ERROR);
        return $this->stack;
    }

    /**
     * Stack.
     *
     * Example in services.yaml:
     *  Kookaburra\Library\Manager\CataloguePagination:
     *      calls:
     *          -   method: setStack
     *      arguments:
     *          - '@request_stack'
     *
     * @param RequestStack $stack
     * @return ReactPaginationManager
     */
    public function setStack(RequestStack $stack): ReactPaginationManager
    {
        $this->stack = $stack;
        return $this;
    }

    /**
     * getRequest
     * @return Request|null
     */
    private function getRequest(): Request
    {
        return $this->getStack()->getCurrentRequest();
    }

    /**
     * getSession
     * @return SessionInterface
     */
    private function getSession(): SessionInterface
    {
        return $this->getRequest()->getSession();
    }

    /**
     * readInitialFilter
     * @return array
     */
    public function readInitialFilter(): array
    {
        $session = $this->getSession();
        $name = StringHelper::toSnakeCase(basename(get_class($this)));
        if ($session->has($name)) {
            $data = $session->get($name);
            $this->setInitialFilter(isset($data['filter']) ? $data['filter'] : []);
            $this->setInitialSearch(isset($data['search']) ? $data['search'] : '');
        }
        return $this->getInitialFilter();
    }

    /**
     * writeInitialFilter
     * @param array $filter
     * @return ReactPaginationManager
     */
    public function writeInitialFilter(array $filter): ReactPaginationManager
    {
        $session = $this->getSession();
        $name = StringHelper::toSnakeCase(basename(get_class($this)));
        // @todo Filter modification ???

        $session->set($name, $filter);
        return $this;
    }

    /**
     * @return bool
     */
    public function isSortList(): bool
    {
        return $this->sortList;
    }

    /**
     * SortList.
     *
     * @param bool $sortList
     * @return ReactPaginationManager
     */
    public function setSortList(bool $sortList): ReactPaginationManager
    {
        $this->sortList = $sortList;
        return $this;
    }

    /**
     * @return bool
     */
    public function isDraggableSort(): bool
    {
        return $this->draggableSort;
    }

    /**
     * DraggableSort.
     *
     * @param bool $draggableSort
     * @return ReactPaginationManager
     */
    public function setDraggableSort(bool $draggableSort): ReactPaginationManager
    {
        $this->draggableSort = $draggableSort;
        return $this;
    }

    /**
     * @return string
     */
    public function getDraggableRoute(): string
    {
        return $this->draggableRoute;
    }

    /**
     * DraggableRoute.
     *
     * @param string $draggableRoute
     * @return ReactPaginationManager
     */
    public function setDraggableRoute(string $draggableRoute): ReactPaginationManager
    {
        $this->draggableRoute = $draggableRoute;
        return $this;
    }

    /**
     * @return string
     */
    public function getAddElementRoute(): string
    {
        return $this->addElementRoute;
    }

    /**
     * AddElementRoute.
     *
     * @param string $addElementRoute
     * @return ReactPaginationManager
     */
    public function setAddElementRoute(string $addElementRoute): ReactPaginationManager
    {
        $this->addElementRoute = $addElementRoute;
        return $this;
    }
}