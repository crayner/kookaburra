'use strict'

import React, { Component } from 'react'
import PropTypes from 'prop-types'
import HeaderRow from "./HeaderRow"
import firstBy from 'thenby'
import PaginationContent from "./PaginationContent"
import PaginationFilter from "./PaginationFilter"
import AreYouSureDialog from "../component/AreYouSureDialog"
import InformationDetail from "../component/InformationDetail"
import {fetchJson} from "../component/fetchJson"
import {isEmpty} from "../component/isEmpty"

export default class PaginationApp extends Component {
    constructor (props) {
        super(props)
        this.pageMax = props.pageMax
        this.row = props.row
        this.content = props.content
        this.filters = props.row.filters
        this.messages = props.translations

        this.sortColumn = this.sortColumn.bind(this)
        this.firstPage = this.firstPage.bind(this)
        this.lastPage = this.lastPage.bind(this)
        this.prevPage = this.prevPage.bind(this)
        this.nextPage = this.nextPage.bind(this)
        this.deleteItem = this.deleteItem.bind(this)
        this.closeConfirm = this.closeConfirm.bind(this)
        this.adjustPageSize = this.adjustPageSize.bind(this)
        this.changeFilter = this.changeFilter.bind(this)
        this.functions = {
            areYouSure: this.areYouSure.bind(this),
            displayInformation: this.displayInformation.bind(this)
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
            filter: '',
        }
    }

    componentDidMount() {
        let result = this.paginateContent(this.content,this.state.offset)
        this.setState({
            results: result,
            control: this.buildControl(this.state.offset, result),
            sizeButtons: this.buildPageSizeControls(this.state.pageMax),
            confirm: false,
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

    paginateContent(content, offset, pageMax = 0, filter = null) {
        if (pageMax === 0)
            pageMax = this.state.pageMax
        if (this.state.filter === '') {
            return content.slice(offset, offset + pageMax)
        }
        filter = filter === null ? this.state.filter : filter

        return content.slice(offset, offset + pageMax)
    }

    firstPage(){
        this.checkOffset(0)
    }

    prevPage() {
        this.checkOffset(this.state.offset - this.state.pageMax)
    }

    nextPage() {
        this.checkOffset(this.state.offset + this.state.pageMax)
    }

    lastPage() {
        let offset = this.state.offset
        while (offset <= this.content.length)
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
            size = this.content.length

        this.checkOffset(this.state.offset, size)
    }

    buildPageSizeControls() {
        let control = []

        if (this.state.filteredContent.length > 10) {
            control.push(<a href={'#'} key={'10'} onClick={() => this.adjustPageSize(10)} className={(this.state.pageMax === 10 ? 'text-blue-600' : 'text-gray-600')}>10,</a>)
            control.push(<a href={'#'} key={'25'} onClick={() => this.adjustPageSize(25)}
                            className={(this.state.pageMax === 25 ? 'text-blue-600' : 'text-gray-600')}>25,</a>)
        }
        if (this.state.filteredContent.length > 25)
            control.push(<a href={'#'} key={'50'} onClick={() => this.adjustPageSize(50)} className={(this.state.pageMax === 50 ? 'text-blue-600' : 'text-gray-600')}>50,</a>)
        if (this.state.filteredContent.length > 50)
            control.push(<a href={'#'} key={'All'} onClick={() => this.adjustPageSize('All')} className={(this.state.pageMax === this.state.filteredContent.length ? 'text-blue-600' : 'text-gray-600')}>All</a>)

        return control
    }

    buildControl() {
        if (this.state.filteredContent.length === 0)
            return []

        let content = this.row.caption.replace('{start}', (this.state.offset + 1))
        content = content.replace('{end}', (this.state.results.length + this.state.offset))
        content = content.replace('{total}', this.state.filteredContent.length)

        let control = []
        if (this.state.offset > 0) {
            control.push(<a href={'#'} key={'first'} onClick={() => this.firstPage()} title={this.row.firstPage}><span className={'text-gray-600 fas fa-angle-double-left fa-fw'}></span></a>)
        }

        if (this.state.filteredContent.length > this.state.pageMax && this.state.offset > 0) {
            control.push(<a href={'#'} key={'prev'} onClick={() => this.prevPage()} title={this.row.prevPage}><span className={'text-gray-600 fas fa-angle-left fa-fw'}></span></a>)
        }

        control.push(<span key={'content'}>{content}</span>)

        if (this.state.filteredContent.length > this.state.pageMax && this.state.pageMax + this.state.offset < this.state.filteredContent.length) {
            control.push(<a href={'#'} key={'next'} onClick={() => this.nextPage()} title={this.row.nextPage}><span className={'text-gray-600 fas fa-angle-right fa-fw'}></span></a>)
            control.push(<a href={'#'} key={'last'} onClick={() => this.lastPage()} title={this.row.lastPage}><span className={'text-gray-600 fas fa-angle-double-right fa-fw'}></span></a>)
        }
        return control
    }

    filterContent(filter) {
        if (isEmpty(filter))
            return this.content

        filter = this.filters[filter]
        const content = this.content.filter(value => {
            if (filter.value === value[filter.contentKey])
                return value
        })

        return content
    }

    changeFilter(e) {
        const filteredContent = this.filterContent(e.target.value)
        let result = this.paginateContent(this.sortContent('', '', filteredContent), 0, 0)
        this.setState({
            results: result,
            control: this.buildControl(this.state.offset, result),
            filter: e.target.value,
            filteredContent: filteredContent,
        })
    }

    render () {
        return (
            <div>
                <div className={'text-xs text-gray-600 text-left'}>
                    <PaginationFilter filter={this.state.filter} filters={this.filters} changeFilter={this.changeFilter} messages={this.messages}/>
                    <span style={{float: 'left'}}>{this.buildPageSizeControls()}</span>
                    <span style={{float: 'right'}}>{this.buildControl()}</span>
                </div>
                <table className={'w-full striped'}>
                    <HeaderRow row={this.row} sortColumn={this.sortColumn} sortColumnName={this.state.sortColumn} sortColumnDirection={this.state.sortDirection} />
                    <PaginationContent row={this.row} content={this.state.results} functions={this.functions} />
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
}
