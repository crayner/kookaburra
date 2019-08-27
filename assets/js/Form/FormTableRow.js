'use strict'

import React, { Component } from 'react'
import PropTypes from 'prop-types'
import FormColumn from "./FormColumn"
import Parser from "html-react-parser"
import FormGeneratePassword from "./FormGeneratePassword"
import FormTextArea from "./FormTextArea"
import FormCollection from "./FormCollection"

export default class FormTableRow extends Component {
    constructor (props) {
        super(props)
        this.form = props.form
        this.columnCount = props.columnCount
        this.functions = props.functions
    }

    render() {
        var row = this.form.row !== undefined ? this.form.row : {}
        row.style = row.style !== undefined && typeof(row.style) === 'object' ? row.style : {}

        if (this.form.block_prefixes.includes('react_collection')) {
            const colStyle = typeof row.columns[0].style === 'object' ? row.columns[0].style : {}
            return (
                <tr className={row.class} style={row.style}>
                    <td className={row.columns[0].class} colSpan={row.columns[0].colspan} style={colStyle}>
                        <FormCollection form={this.form} functions={this.functions} columnCount={this.columnCount} />
                    </td>
                </tr>
            )
        }


        // header
        if (this.form.block_prefixes.includes('header')) {
            const columns = row.columns.map((column,key) => {
                const columnStyle = column.style !== undefined && typeof(column.style) === 'object' ? column.style : {}
                return (<td className={column.class} style={columnStyle} key={key}>
                    <h3>{this.form.label}</h3>
                </td> )
            })
            return (
                <tr className={row.class} style={row.style}>
                    {columns}
                </tr>
            )
        }

        // paragraph
        if (this.form.block_prefixes.includes('paragraph')) {
            const columns = row.columns.map((column,key) => {
                const columnStyle = column.style !== undefined && typeof(column.style) === 'object' ? column.style : {}
                return (<td className={column.class} style={columnStyle} key={key}>
                    <div className={column.wrapper.class}>{Parser(this.form.help)}</div>
                </td> )
            })
            return (
                <tr className={row.class} style={row.style}>
                    {columns}
                </tr>
            )
        }

        // email
        if (this.form.block_prefixes.includes('email')) {
            this.form.type = 'email'
            const columns = row.columns.map((column,key) => {
                column.style = column.style !== undefined && typeof(column.style) === 'object' ? column.style : {}
                return (<td className={column.class} style={column.style} key={key}>
                    <FormColumn functions={this.functions} form={this.form} column={column} />
                </td> )
            })
            return (
                <tr className={row.class} style={row.style}>
                    {columns}
                </tr>
            )
        }

        // hidden
        if (this.form.block_prefixes.includes('hidden')) {
            this.form.type = 'hidden'
            row.style.display = 'none'
            return (
                <tr style={{display: 'none'}}>
                    <td>
                        <input type={'hidden'} defaultValue={this.form.value} id={this.form.id} name={this.form.full_name} />
                    </td>
                </tr>
            )
        }

        // url
        if (this.form.block_prefixes.includes('url')) {
            this.form.type = 'url'
            const columns = row.columns.map((column,key) => {
                column.style = column.style !== undefined && typeof(column.style) === 'object' ? column.style : {}
                return (<td className={column.class} style={column.style} key={key}>
                    <FormInput functions={this.functions} form={this.form} column={column} />
                </td> )
            })
            return (
                <tr className={row.class} style={row.style}>
                    {columns}
                </tr>
            )
        }

        // password
        if (this.form.block_prefixes.includes('password')) {
            this.form.type = 'password'
            const columns = row.columns.map((column,key) => {
                column.style = column.style !== undefined && typeof(column.style) === 'object' ? column.style : {}
                return (<td className={column.class} style={column.style} key={key}>
                    <FormColumn functions={this.functions} form={this.form} column={column} />
                </td> )
            })
            return (
                <tr className={row.class} style={row.style}>
                    {columns}
                </tr>
            )
        }

        // file
        if (this.form.block_prefixes.includes('file')) {
            this.form.type = 'file'
            const columns = row.columns.map((column,key) => {
                column.style = column.style !== undefined && typeof(column.style) === 'object' ? column.style : {}
                return (<td className={column.class} style={column.style} key={key}>
                    <FormInput functions={this.functions} form={this.form} column={column} />
                </td> )
            })
            return (
                <tr className={row.class} style={row.style}>
                    {columns}
                </tr>
            )
        }

        // textarea
        if (this.form.block_prefixes.includes('textarea')) {
            return (<FormTextArea form={this.form} row={row} />)
        }

        // text
        if (this.form.block_prefixes.includes('text')) {
            this.form.type = 'text'
            const columns = row.columns.map((column,key) => {
                column.style = column.style !== undefined && typeof(column.style) === 'object' ? column.style : {}
                return (<td className={column.class} style={column.style} key={key}>
                    <FormColumn functions={this.functions} form={this.form} column={column} />
                </td> )
            })
            return (
                <tr className={row.class} style={row.style}>
                    {columns}
                </tr>
            )
        }

        // submit
        if (this.form.block_prefixes.includes('submit')) {
            this.form.type = 'submit'
            const columns = row.columns.map((column,key) => {
                column.style = column.style !== undefined && typeof(column.style) === 'object' ? column.style : {}
                return (<td className={column.class} style={column.style} key={key}>
                    <FormColumn functions={this.functions} form={this.form} column={column} />
                </td> )
            })
            return (
                <tr className={row.class} style={row.style}>
                    {columns}
                </tr>
            )
        }

        // toggle
        if (this.form.block_prefixes.includes('toggle')) {
            const columns = row.columns.map((column,key) => {
                column.style = column.style !== undefined && typeof(column.style) === 'object' ? column.style : {}
                return (<td className={column.class} style={column.style} key={key}>
                    <FormColumn functions={this.functions} form={this.form} column={column} />
                </td> )
            })
            return (
                <tr className={row.class} style={row.style}>
                    {columns}
                </tr>
            )
        }

        // generate_password
        if (this.form.block_prefixes.includes('generate_password')) {
            return (<FormGeneratePassword form={this.form} row={row} />)
        }

        // choices
        if (this.form.block_prefixes.includes('choice')) {
            const columns = row.columns.map((column,key) => {
                column.style = column.style !== undefined && typeof(column.style) === 'object' ? column.style : {}
                return (<td className={column.class} style={column.style} key={key}>
                    <FormColumn functions={this.functions} form={this.form} column={column} />
                </td> )
            })
            return (
                <tr className={row.class} style={row.style}>
                    {columns}
                </tr>
            )
        }

        // children
        if (typeof this.form.children === 'object' && Object.keys(this.form.children).length > 0)
        {
            return Object.keys(this.form.children).map(key => {
                let child = this.form.children[key]
                return (<FormTableRow form={child} key={key} columnCount={this.columnCount} functions={this.functions} />)
            })
        }

        console.log(this.form)
        return (
            <tr className={row.class} style={row.style}>
                {null}
            </tr>
        )
    }
}

FormTableRow.propTypes = {
    form: PropTypes.object.isRequired,
    functions: PropTypes.object.isRequired,
    columnCount: PropTypes.number.isRequired,
}
