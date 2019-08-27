'use strict'

import React, { Component } from 'react'
import PropTypes from 'prop-types'
import FormCollectionElement from "./FormCollectionElement"
import {fetchJson} from "../component/fetchJson"
import Messages from "../component/Messages"

export default class FormCollection extends Component {
    constructor (props) {
        super(props)
        this.form = props.form
        this.columnCount = props.columnCount
        this.functions = props.functions

        this.columnTemplate = {...props.form.column}
        this.columnTemplate.allow_add = props.form.allow_add
        this.columnTemplate.allow_delete = props.form.allow_delete

        this.functions.deleteElement = this.deleteElement.bind(this)
        this.state = {
            errors: [],
        }
    }

    deleteElement(element){
        let route = this.form.element_delete_route
        Object.keys(this.form.element_delete_options).map(find => {
            const elementKey = this.form.element_delete_options[find]
            route = route.replace(find, element.children[elementKey].value)
        })
        fetchJson(route, [], false)
            .then((data) => {
                this.form = data.form === [] ? this.form : data.form
                this.setState({
                    errors: data.errors,
                })
            })
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
console.log(this.state.errors)
        return (
            <div className={'collection'}>
                <Messages messages={this.state.errors} />
                <table style={table.style} className={table.class}>
                    {headerRow}
                    <tbody>
                        {collectionElements}
                    </tbody>
                </table>
            </div>
        )
    }
}


FormCollection.propTypes = {
    form: PropTypes.object.isRequired,
    functions: PropTypes.object.isRequired,
    columnCount: PropTypes.number.isRequired,
}
