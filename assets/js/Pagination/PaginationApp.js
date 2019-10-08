'use strict'

import React, { Component } from 'react'
import PropTypes from 'prop-types'
import HeaderRow from "./HeaderRow"
import firstBy from 'thenby'
import PaginationContent from "./PaginationContent"
import AreYouSureDialog from "../component/AreYouSureDialog"

export default class PaginationApp extends Component {
    constructor (props) {
        super(props)
        this.pageMax = props.pageMax
        this.row = props.row
        this.content = props.content
        this.messages = props.translations

        this.sortColumn = this.sortColumn.bind(this)
        this.firstPage = this.firstPage.bind(this)
        this.lastPage = this.lastPage.bind(this)
        this.prevPage = this.prevPage.bind(this)
        this.nextPage = this.nextPage.bind(this)
        this.deleteItem = this.deleteItem.bind(this)
        this.closeConfirm = this.closeConfirm.bind(this)
        this.adjustPageSize = this.adjustPageSize.bind(this)
        this.functions = {
            areYouSure: this.areYouSure.bind(this)
        }
        this.path = ''

        this.state = {
            sortColumn: '',
            sortDirection: '',
            results: [],
            offset: 0,
            controls: [],
            pageMax: this.pageMax,
            sizeButtons: [],
            confirm: false,
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

    areYouSure(path) {
        this.path = path
        this.setState({
            confirm: true
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
            confirm: false
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
        if (columnName === this.state.sortColumn)
        {
            direction = direction === 'down' ? 'up' : 'down'
        } else {
            direction = 'down'
        }

        let result = this.paginateContent(this.sortContent(columnName,direction,this.content), this.state.offset)
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
        this.checkOffset(offset, this.state.pageMax)
    }

    checkOffset(offset, pageMax = 0) {
        if (pageMax === 0)
            pageMax = this.state.pageMax

        while (offset > this.content.length)
            offset = offset - pageMax

        if (pageMax >= this.content.length)
            offset = 0

        if (offset < 0)
            offset = 0

        let result = this.paginateContent(this.sortContent('', '', this.content), offset, pageMax)

        this.setState({
            offset: offset,
            results: result,
            control: this.buildControl(offset, result, pageMax),
            pageMax: pageMax,
            sizeButtons: this.buildPageSizeControls(pageMax)
        })
    }

    adjustPageSize(size) {
        if (size === 'All')
            size = this.content.length

        this.checkOffset(this.state.offset, size)
    }

    buildPageSizeControls(pageMax) {
        let control = []

        control.push(<a href={'#'} key={'10'} onClick={() => this.adjustPageSize(10)} className={(pageMax === 10 ? 'text-blue-600' : 'text-gray-600')}>10,</a>)
        control.push(<a href={'#'} key={'25'} onClick={() => this.adjustPageSize(25)} className={(pageMax === 25 ? 'text-blue-600' : 'text-gray-600')}>25,</a>)
        control.push(<a href={'#'} key={'50'} onClick={() => this.adjustPageSize(50)} className={(pageMax === 50 ? 'text-blue-600' : 'text-gray-600')}>50,</a>)
        control.push(<a href={'#'} key={'All'} onClick={() => this.adjustPageSize('All')} className={(pageMax === this.content.length ? 'text-blue-600' : 'text-gray-600')}>All</a>)

        return control
    }

    buildControl(offset, result, pageMax = 0) {
        if (this.content.length === 0)
            return []
        
        if (pageMax === 0)
            pageMax = this.state.pageMax

        let content = this.row.caption.replace('{start}', (offset + 1))
        content = content.replace('{end}', (result.length + offset))
        content = content.replace('{total}', this.content.length)

        let control = []
        if (offset > 0) {
            control.push(<a href={'#'} key={'first'} onClick={() => this.firstPage()} title={this.row.firstPage}><span className={'text-gray-600 fas fa-angle-double-left fa-fw'}></span></a>)
        }

        if (this.content.length > pageMax && offset > 0) {
            control.push(<a href={'#'} key={'prev'} onClick={() => this.prevPage()} title={this.row.prevPage}><span className={'text-gray-600 fas fa-angle-left fa-fw'}></span></a>)
        }

        control.push(<span key={'content'}>{content}</span>)

        if (this.content.length > pageMax && pageMax + offset < this.content.length) {
            control.push(<a href={'#'} key={'next'} onClick={() => this.nextPage()} title={this.row.nextPage}><span className={'text-gray-600 fas fa-angle-right fa-fw'}></span></a>)
            control.push(<a href={'#'} key={'last'} onClick={() => this.lastPage()} title={this.row.lastPage}><span className={'text-gray-600 fas fa-angle-double-right fa-fw'}></span></a>)
        }
        return control
    }

    render () {
        return (
            <div>
                <div className={'text-xs text-gray-600 text-left'}>{this.state.sizeButtons}
                <div className={'text-xs text-gray-600 text-right'} style={{marginTop: '-12px'}}>{this.state.control}</div></div>
                <table className={'w-full striped'}>
                    <HeaderRow row={this.row} sortColumn={this.sortColumn} sortColumnName={this.state.sortColumn} sortColumnDirection={this.state.sortDirection} />
                    <PaginationContent row={this.row} content={this.state.results} functions={this.functions} />
                </table>
                <AreYouSureDialog messages={this.messages} doit={() => this.deleteItem(this.path)} cancel={() => this.closeConfirm()} confirm={this.state.confirm} />
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
