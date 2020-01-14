'use strict'

import React, { Component } from 'react'
import PropTypes from 'prop-types'
import HeaderRow from "./HeaderRow"
import firstBy from 'thenby'
import PaginationContent from "./PaginationContent"
import PaginationFilter from "./PaginationFilter"
import PaginationSearch from "./PaginationSearch"
import AreYouSureDialog from "../component/AreYouSureDialog"
import InformationDetail from "../component/InformationDetail"
import {fetchJson} from "../component/fetchJson"
import {buildState, getParentFormName, mergeParentForm, openUrl, trans} from "../Container/ContainerFunctions"
import Messages from "../component/Messages"

export default class PaginationApp extends Component {
    constructor (props) {
        super(props)
        this.pageMax = props.pageMax
        this.row = props.row
        this.content = props.content
        this.filters = props.row.filters
        this.messages = props.translations
        this.search = props.row.search
        this.filterGroups = props.row.filterGroups
        this.contentLoader = props.contentLoader
        this.defaultFilter = props.row.defaultFilter
        this.initialFilter = props.initialFilter
        this.addElementRoute = props.addElementRoute
        this.draggableSort = props.draggableSort
        this.columnCount = 0
        this.storeFilterURL = props.storeFilterURL
        this.draggableRoute = props.draggableRoute

        this.sortColumn = this.sortColumn.bind(this)
        this.firstPage = this.firstPage.bind(this)
        this.lastPage = this.lastPage.bind(this)
        this.prevPage = this.prevPage.bind(this)
        this.nextPage = this.nextPage.bind(this)
        this.deleteItem = this.deleteItem.bind(this)
        this.closeConfirm = this.closeConfirm.bind(this)
        this.adjustPageSize = this.adjustPageSize.bind(this)
        this.changeFilter = this.changeFilter.bind(this)
        this.changeSearch = this.changeSearch.bind(this)
        this.clearSearch = this.clearSearch.bind(this)
        this.translate = this.translate.bind(this)

        this.functions = {
            areYouSure: this.areYouSure.bind(this),
            displayInformation: this.displayInformation.bind(this),
            dropEvent: this.dropEvent.bind(this),
        }
        this.path = ''

        this.state = {
            sortColumn: '',
            sortDirection: '',
            results: [],
            offset: 0,
            pageMax: this.pageMax,
            confirm: false,
            information: false,
            filteredContent: this.content,
            filter: this.initialFilter,
            filterGroups: {},
            search: '',
            messages: [],
        }
    }

    componentDidMount() {
        this.columnCount = 0
        this.row.columns.map(column => {
            if (column.dataOnly === false)
                this.columnCount = this.columnCount + 1
        })

        if (this.row.actions.length > 0)
            this.columnCount = this.columnCount + 1

        if (this.contentLoader !== false) {
            this.row.emptyContent = this.messages['Loading Content...']
            this.loadContent()
        }

        this.changeFilter(null)

        if (this.draggableSort) {
            let info = {}
            info.class = 'info'
            info.message = 'Items rows can be dragged into the correct position.'
            info.message = 'Items rows can be dragged into the correct position.'
            info.close = false
            this.setState({
                messages: [info],
            })
        }

    }

    translate(id) {
        return trans(this.messages, id)
    }

    loadContent() {
        fetchJson(this.contentLoader, {}, false)
            .then(data => {
                if (data.status === 'success'){
                    this.content = data.content
                    this.pageMax = data.pageMax
                    let result = this.paginateContent(this.content,0, this.pageMax)
                    this.row.emptyContent = this.messages['There are no records to display.']
                    this.setState({
                        results: result
                    })
                } else {
                    this.setState({
                        information: data.message
                    })
                }
            }).catch(error => {
                console.error(error)
                this.setState({
                    information: error
                })
        })
    }

    areYouSure(path, content) {
        this.path = path
        this.setState({
            confirm: true,
        })
    }

    displayInformation(path, content) {
        this.path = path
        fetchJson(
            path,
            {},
            false
        ).then(data => {
            this.setState({
                information: data,
            })
        })
    }

    deleteItem(path) {
        this.setState({
            confirm: false
        })
        window.open(path,'_self')
    }

    closeConfirm(){
        this.path = ''
        this.setState({
            confirm: false,
            information: false
        })
    }

    dropEvent(ev) {
        ev.preventDefault()
        var data = ev.dataTransfer.getData("text")
        let source = data.replace('pagination', '')
        let target = ev.target.parentNode.id.replace('pagination', '')
        let route = this.draggableRoute.replace('__source__', source).replace('__target__',target)
        fetchJson(route,
            {},
            false).then(data => {
                let errors = this.state.messages
                data.errors.map(error => {
                    errors.push(error)
                })
                if (data.status === 'success') {
                    this.content = data.content
                    let result = this.paginateContent(this.content,0, this.state.pageMax)
                    this.setState({
                        results: result,
                        messages: errors,
                    })
                } else if (data.status === 'error') {
                    this.setState({
                        messages: errors,
                    })
                }
        })
    }

    sortColumn(columnName){
        let column = {}
        Object.keys(this.row.columns).filter(columnKey=> {
            if(this.row.columns[columnKey].contentKey === columnName)
                column = this.row.columns[columnKey]
        })
        if (column.sort !== true)
            return

        let direction = this.state.sortDirection

        if (typeof columnName === 'object')
            columnName = columnName[0]

        if (columnName === this.state.sortColumn)
        {
            direction = direction === 'down' ? 'up' : 'down'
        } else {
            direction = 'down'
        }

        let result = this.paginateContent(this.sortContent(columnName,direction,this.state.filteredContent), this.state.offset)
        this.setState({
            sortColumn: columnName,
            sortDirection: direction,
            results: result,
            control: this.buildControl(this.state.offset, result)
        })
    }

    sortContent(name, direction, content)
    {
        if (name === '') {
            name = this.state.sortColumn
            direction = this.state.sortDirection
        }
        if (name === '')
            return content

        return content.sort(
            firstBy(name, direction === 'up' ? -1 : 1)
        )
    }

    paginateContent(content, offset, pageMax = 0) {
        if (pageMax === 0)
            pageMax = this.state.pageMax

        return content.slice(offset, offset + pageMax)
    }

    firstPage(){
        this.checkOffset(this.state.filteredContent,0)
    }

    prevPage() {
        this.checkOffset(this.state.filteredContent,this.state.offset - this.state.pageMax)
    }

    nextPage() {
        this.checkOffset(this.state.filteredContent,this.state.offset + this.state.pageMax)
    }

    lastPage() {
        let offset = this.state.offset
        while (offset <= this.state.filteredContent.length)
            offset = offset + this.state.pageMax
        this.checkOffset(this.state.filteredContent, offset, this.state.pageMax)
    }

    checkOffset(filteredContent, offset, pageMax = 0) {
        if (pageMax === 0)
            pageMax = this.state.pageMax

        while (offset > filteredContent.length)
            offset = offset - pageMax

        if (pageMax >= filteredContent.length)
            offset = 0

        if (offset < 0)
            offset = 0

        let result = this.paginateContent(this.sortContent('', '', filteredContent), offset, pageMax)

        this.setState({
            offset: offset,
            results: result,
            pageMax: pageMax,
            sizeButtons: this.buildPageSizeControls(pageMax),
            filteredContent: filteredContent,
        })
    }

    adjustPageSize(size) {
        if (size === 'All')
            size = this.state.filteredContent.length

        this.checkOffset(this.state.filteredContent,this.state.offset, size)
    }

    buildPageSizeControls() {
        let control = []

        if (this.state.filteredContent.length > 10) {
            control.push(<a href={'#'} key={'10'} onClick={() => this.adjustPageSize(10)} className={(this.state.pageMax === 10 ? 'text-blue-600' : 'text-gray-600')}>10</a>)
            control.push(<a href={'#'} key={'25'} onClick={() => this.adjustPageSize(25)} className={(this.state.pageMax === 25 ? 'text-blue-600' : 'text-gray-600')}>,25</a>)
        }
        if (this.state.filteredContent.length > 25)
            control.push(<a href={'#'} key={'50'} onClick={() => this.adjustPageSize(50)} className={(this.state.pageMax === 50 ? 'text-blue-600' : 'text-gray-600')}>,50</a>)
        if (this.state.filteredContent.length > 50)
            control.push(<a href={'#'} key={'100'} onClick={() => this.adjustPageSize(100)} className={(this.state.pageMax === 100 ? 'text-blue-600' : 'text-gray-600')}>,100</a>)
        if (this.state.filteredContent.length > 100)
            control.push(<a href={'#'} key={'All'} onClick={() => this.adjustPageSize('All')} className={(this.state.pageMax === this.state.filteredContent.length ? 'text-blue-600' : 'text-gray-600')}>,{this.messages['All']}</a>)

        return control
    }

    buildControl() {
        if (this.state.filteredContent.length === 0 && this.addElementRoute === '')
            return []

        let control = []
        if (this.addElementRoute !== '') {
            control.push(<a href={'#'} key={'add'} onClick={() => openUrl(this.addElementRoute, '_self')} title={this.row.addElement}><span className={'text-gray-600 fas fa-plus-circle fa-fw'}/></a>)
        }
        if (this.state.filteredContent.length === 0)
            return control

        let content = this.row.caption.replace('{start}', (this.state.offset + 1))
        content = content.replace('{end}', (this.state.results.length + this.state.offset))
        content = content.replace('{total}', this.state.filteredContent.length)

        if (this.state.offset > 0) {
            control.push(<a href={'#'} key={'first'} onClick={() => this.firstPage()} title={this.row.firstPage}><span className={'text-gray-600 fas fa-angle-double-left fa-fw'}/></a>)
        }

        if (this.state.filteredContent.length > this.state.pageMax && this.state.offset > 0) {
            control.push(<a href={'#'} key={'prev'} onClick={() => this.prevPage()} title={this.row.prevPage}><span className={'text-gray-600 fas fa-angle-left fa-fw'}/></a>)
        }

        control.push(<span key={'content'}>{content}</span>)

        if (this.state.filteredContent.length > this.state.pageMax && this.state.pageMax + this.state.offset < this.state.filteredContent.length) {
            control.push(<a href={'#'} key={'next'} onClick={() => this.nextPage()} title={this.row.nextPage}><span className={'text-gray-600 fas fa-angle-right fa-fw'}/></a>)
            control.push(<a href={'#'} key={'last'} onClick={() => this.lastPage()} title={this.row.lastPage}><span className={'text-gray-600 fas fa-angle-double-right fa-fw'}/></a>)
        }
        return control
    }

    filterContent(filter, search) {
        if (filter === [])
            filter = this.state.filter

        let content = this.content
        filter.map(filterValue => {
            const filterDetail = this.filters[filterValue]
            let filtered = content.filter(value => {
                if (filterDetail.value === value[filterDetail.contentKey])
                    return value
            })
            content = filtered
        })

        if (this.search && search !== '') {
            search = search.toLowerCase()
            let filtered = []
            content.filter(value => {
                let selected = false
                this.row.columns.map(column => {
                    if (column.search) {
                        column.contentKey.map(key => {
                            if (!selected && typeof value[key] !== 'undefined' && value[key].toLowerCase().includes(search)) {
                                selected = true
                                filtered.push(value)
                            }
                        })
                    }
                })
            })
            content = filtered
        }
        return content
    }

    changeFilter(value) {
        let filterGroups = {...this.state.filterGroups}
        if (value === null) {
            if (this.defaultFilter === null) {
                let result = this.paginateContent(this.content,this.state.offset)
                this.setState({
                    results: result,
                    control: this.buildControl(this.state.offset, result),
                    sizeButtons: this.buildPageSizeControls(this.state.pageMax),
                    confirm: false,
                })
                return
            } else if (Object.keys(filterGroups).length === 0) {
                filterGroups = {}
                if (this.state.filter.length > 0) {
                    let loop = 0
                    this.state.filter.map(name => {
                        const filter = this.filters[name]
                        if (loop === 0) {
                            filterGroups[filter.group] = ''
                            value = filter
                        } else {
                            filterGroups[filter.group] = filter.value
                        }
                        loop = loop + 1
                    })
                }

                Object.keys(this.defaultFilter).map(key => {
                    if (value === null) {
                        value = this.defaultFilter[key]
                        filterGroups[value.group] = ''
                    } else {
                        const x = this.defaultFilter[key]
                        filterGroups[x.group] = x.name
                    }
                })
            }
        } else if (typeof value.group === 'undefined') {
            if (value.target.value === '')
                return
            value = this.filters[value.target.value]
        }

        let filter = this.state.filter
        if (this.filterGroups) {
            let was = filterGroups[value.group]
            if (was === undefined) {
                filterGroups[value.group] = value.name
            } else if (was === value.name) {
                delete filterGroups[value.group]
            } else {
                filterGroups[value.group] = value.name
            }

            if (this.defaultFilter !== null) {
                Object.keys(this.defaultFilter).map(key => {
                    value = this.defaultFilter[key]
                    if (typeof filterGroups[value.group] === 'undefined')
                        filterGroups[value.group] = value.name
                })

            }

            filter = Object.keys(filterGroups).map(q => {
                return filterGroups[q]
            })
        } else {
            filter = [value.name]
        }
        const filteredContent = this.filterContent(filter, this.state.search)
        let result = this.paginateContent(this.sortContent('', '', filteredContent), 0, 0)
        this.setState({
            results: result,
            control: this.buildControl(this.state.offset, result),
            filter: filter,
            filteredContent: filteredContent,
            filterGroups: filterGroups
        })
    }

    changeSearch(e) {
        const filteredContent = this.filterContent(this.state.filter, e.target.value)

        let results = this.paginateContent(this.sortContent('', '', filteredContent), 0, 0)

        this.setState({
            search: e.target.value,
            results: results,
            control: this.buildControl(this.state.offset, results),
            filteredContent: filteredContent
        })
    }

    clearSearch(e) {
        const filteredContent = this.filterContent(this.state.filter, '')

        let results = this.paginateContent(this.sortContent('', '', filteredContent), 0, 0)

        this.setState({
            search: '',
            results: results,
            control: this.buildControl(this.state.offset, results),
            filteredContent: filteredContent
        })
    }

    searchFilter() {
        if (this.search)
            return ''
        if (Object.keys(this.filters).length > 0)
            return ''
        return 'none'
    }

    storeFilter()
    {
        if (null === this.storeFilterURL)
            return
        fetchJson(
            this.storeFilterURL,
            {method: 'POST', body: JSON.stringify(this.state.filter)},
            false
        )
    }

    render () {
        this.storeFilter()
        return (
            <div>
                <div className={'text-xs text-gray-600 text-left'}>
                    <Messages messages={this.state.messages} translate={this.translate} />
                    <span style={{float: 'left', clear: 'both'}}>{this.buildPageSizeControls()}</span>
                    <span style={{float: 'right'}}>{this.buildControl()}</span>
                </div>
                <table className={'w-full striped'}>
                    <thead>
                        <tr style={{display: this.searchFilter()}}>
                            <td colSpan={this.columnCount}>
                                <table className={'noIntBorder fullWidth relative'}>
                                    <tbody>
                                        <PaginationSearch search={this.search} messages={this.messages} clearSearch={this.clearSearch} changeSearch={this.changeSearch} searchValue={this.state.search} />
                                        <PaginationFilter filter={this.state.filter} filters={this.filters} changeFilter={this.changeFilter} messages={this.messages} filterGroups={this.state.filterGroups} defaultFilters={this.defaultFilter !== null} />
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <HeaderRow row={this.row} sortColumn={this.sortColumn} sortColumnName={this.state.sortColumn} sortColumnDirection={this.state.sortDirection} />
                    </thead>
                    <PaginationContent row={this.row} content={this.state.results} functions={this.functions} draggableSort={this.draggableSort} />
                </table>
                <AreYouSureDialog messages={this.messages} doit={() => this.deleteItem(this.path)} cancel={() => this.closeConfirm()} confirm={this.state.confirm} />
                <InformationDetail messages={this.messages} cancel={() => this.closeConfirm()} information={this.state.information} />
            </div>
        )
    }
}

PaginationApp.propTypes = {
    pageMax: PropTypes.number.isRequired,
    row: PropTypes.object.isRequired,
    content: PropTypes.array.isRequired,
    translations: PropTypes.object.isRequired,
    storeFilterURL: PropTypes.string,
    draggableRoute: PropTypes.string,
}

PaginationApp.defaultProps = {
    draggableRoute: '',
}