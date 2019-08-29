'use strict'

import React, { Component } from 'react'
import PropTypes from 'prop-types'
import Row from "./Template/Table/Row"

export default class FormApp extends Component {
    constructor (props) {
        super(props)
        this.form = props.form
        this.columnCount = props.form.column_count
        this.globalForm = props.globalForm
        this.functions = props.functions
        this.columns = props.form.columns
    }

    render() {
        if (this.form.template === 'table'){
            const rows = Object.keys(this.form.children).map(key => {
                const form = this.form.children[key]
                return (<Row key={key} form={form} functions={this.functions} columns={this.columns}/>)
            })

            let table_attr = {}
            table_attr.className = 'smallIntBorder fullWidth standardForm relative'
            if (this.form.row_class !== null) table_attr.className = this.form.row_class

            if (this.globalForm) {
                return (<table {...table_attr}>
                    <tbody>
                    {rows}
                    </tbody>
                </table>)
            }

            return (<form action={this.form.action}
                          id={this.form.id}
                          {...this.form.attr}
                          method={this.form.method !== undefined ? this.form.method : 'POST'}>
                        <table {...table_attr}>
                            <tbody>
                            {rows}
                            </tbody>
                        </table>
                    </form>
            )
        }
        // Future Expansion for grid not table
        return ''
    }
}

FormApp.propTypes = {
    form: PropTypes.object.isRequired,
    globalForm: PropTypes.bool.isRequired,
    functions: PropTypes.object.isRequired,
}
