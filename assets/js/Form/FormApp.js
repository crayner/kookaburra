'use strict'

import React, { Component } from 'react'
import PropTypes from 'prop-types'
import FormTableRow from "./FormTableRow"

export default class FormApp extends Component {
    constructor (props) {
        super(props)
        this.form = props.form
        this.columnCount = props.form.column_count
    }

    render() {
        if (this.form.template_style === 'table'){
            const row = this.form.row
            const rows = Object.keys(this.form.children).map(key => {
                const form = this.form.children[key]
                return (<FormTableRow form={form} key={key} columnCount={this.columnCount} />)
            })

            return (
                <form action={this.form.action} className={row.form.class} id={this.form.id} encType={row.form.enctype} method={this.form.method !== undefined ? this.form.method : row.form.method }>
                    <table className={row.table.class} cellSpacing={row.table.cellspacing}>
                        <tbody>
                            {rows}
                        </tbody>
                    </table>
                </form>
            )
        }
        return ''
    }
}

FormApp.propTypes = {
    form: PropTypes.object.isRequired,
}
