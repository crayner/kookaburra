'use strict'

import React, { Component } from 'react'
import PropTypes from 'prop-types'
import HeaderRow from "./HeaderRow"
import firstBy from 'thenby'
import PaginationContent from "./PaginationContent"

export default class PaginationApp extends Component {
    constructor (props) {
        super(props)
        this.pageMax = props.pageMax
        this.row = props.row
        this.content = props.content

        this.sortColumn = this.sortColumn.bind(this)
        this.firstPage = this.firstPage.bind(this)
        this.lastPage = this.lastPage.bind(this)
        this.prevPage = this.prevPage.bind(this)
        this.nextPage = this.nextPage.bind(this)

        this.state = {
            sortColumn: '',
            sortDirection: '',
            results: [],
            offset: 0,
            controls: [],
        }
    }

    componentDidMount() {
        let result = this.paginateContent(this.content,this.state.offset)
        this.setState({
            results: result,
            control: this.buildControl(this.state.offset, result)
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

    paginateContent(content, offset) {
        return content.slice(offset, this.pageMax)
    }

    firstPage(){
        this.checkOffset(0)
    }

    prevPage() {
        this.checkOffset(this.state.offset - this.pageMax)
    }

    nextPage() {
        this.checkOffset(this.state.offset + this.pageMax)
    }

    lastPage() {
        let offset = this.state.offset
        while (offset <= this.content.length)
            offset = offset + this.pageMax

    }

    checkOffset(offset) {
        while (offset > this.content.length)
            offset = offset - this.pageMax

        if (offset < 0)
            offset = 0

        let result = this.paginateContent(this.sortContent('', '', this.content), offset)

        this.setState({
            offset: offset,
            results: result,
            control: this.buildControl(offset, result),
        })
    }

    buildControl(offset, result) {
        if (this.content.length === 0)
            return []

        let content = this.row.caption.replace('{start}', (offset + 1))
        content = content.replace('{end}', (result.length + offset))
        content = content.replace('{total}', this.content.length)

        let control = []
        if (offset > 0) {
            control.push(<a href={'#'} onClick={() => this.firstPage()} title={this.row.firstPage}><span className={'text-gray-600 fas fa-angle-double-left fa-fw'}></span></a>)
        }

        if (this.content.length > this.pageMax && offset > 0) {
            control.push(<a href={'#'} onClick={() => this.prevPage()} title={this.row.prevPage}><span className={'text-gray-600 fas fa-chevron-left fa-fw'}></span></a>)
        }

        control.push(content)

        if (this.content.length > this.pageMax && this.pageMax + offset < this.content.length) {
            control.push(<a href={'#'} onClick={() => this.nextPage()} title={this.row.nextPage}><span className={'text-gray-600 fas fa-chevron-right fa-fw'}></span></a>)
            control.push(<a href={'#'} onClick={() => this.lastPage()} title={this.row.lastPage}><span className={'text-gray-600 fas fa-angle-double-right fa-fw'}></span></a>)
        }
        return control
    }

    render () {
        return (
            <div>
                <div className={'w-full text-xs right text-gray-600'}>{this.state.control}</div>
                <table className={'w-full striped'}>
                    <HeaderRow row={this.row} sortColumn={this.sortColumn} sortColumnName={this.state.sortColumn} sortColumnDirection={this.state.sortDirection} />
                    <PaginationContent row={this.row} content={this.state.results} contentCount={this.content.length} offset={this.state.offset}/>
                </table>
            </div>
        )
    }
}

PaginationApp.propTypes = {
    pageMax: PropTypes.number.isRequired,
    row: PropTypes.object.isRequired,
    content: PropTypes.array.isRequired,
}
