'use strict'

import React, { Component } from 'react'
import PropTypes from 'prop-types'
import FormCollectionElement from "./FormCollectionElement"

export default class FormCollection extends Component {
    constructor (props) {
        super(props)
        this.form = {...props.form}
        this.columnCount = props.columnCount
        this.functions = props.functions

        this.columnTemplate = {...props.form.column}
        this.columnTemplate.allow_add = props.form.allow_add
        this.columnTemplate.allow_delete = props.form.allow_delete

        this.functions.deleteElement = this.deleteElement.bind(this)
    }

    deleteElement(element){
        console.log(element)
    }

    render() {
        var table = this.form.row.table !== undefined ? this.form.row.table : {}
        table.style = typeof(table.style) === 'object' ? table.style : {}

        let headerRow = this.form.row.thead !== undefined && typeof(this.form.row.thead) === 'object' ? (
            <thead>
                <tr className={this.form.row.thead.class}>
                    {this.form.row.thead.columns.map((column, columnKey) => {
                        return <th className={column.class} key={columnKey}>{column.label}</th>
                    })}
                </tr>
            </thead>
        ) : null

        let collectionElements = this.form.children.map((child, childKey) => {
            return (<FormCollectionElement
                element={child}
                template={this.columnTemplate}
                key={childKey}
                functions={this.functions}
            />)
        })



        return (
            <table style={table.style} className={table.class}>
                {headerRow}
                <tbody>
                    {collectionElements}
                </tbody>
            </table>
        )
    }
}


FormCollection.propTypes = {
    form: PropTypes.object.isRequired,
    functions: PropTypes.object.isRequired,
    columnCount: PropTypes.number.isRequired,
}
