'use strict'

import React, { Component } from 'react'
import PropTypes from 'prop-types'
import {isEmpty} from "../component/isEmpty"
import Parser from "html-react-parser"
import CKEditor from '@ckeditor/ckeditor5-react';
import DocumentEditor from '@ckeditor/ckeditor5-build-classic';

export default class FormTextArea extends Component {
    constructor(props) {
        super(props)
        this.form = props.form
        this.row = props.row
    }

    render() {

        const columns = this.row.columns.map((column,columnKey) => {
            if (column.formElements.includes('label_help')) {
                return (<td className={column.class} key={columnKey}>
                        <label htmlFor={this.form.id} className={column.label.class} style={column.style}>{form.label}{isEmpty(this.form.help) ? '' : (<span className={column.help.class} id={this.form.id + '_help'}><br/>{this.form.help_html ? Parser(this.form.help) : this.form.help}</span>)}</label>
                    </td>)
            }

            if (column.formElements.includes('help')) {
                return (<td className={column.class} key={columnKey}>
                    <span className={column.help.class} id={this.form.id + '_help'}>{this.form.help_html ? Parser(this.form.help) : this.form.help}</span>
                </td>)
            }

            this.form.attr.class = this.form.attr.class !== undefined && typeof(this.form.attr.class) === 'string' ? this.form.attr.class : ''
            this.form.attr.style = this.form.attr.style !== undefined && typeof(this.form.attr.style) === 'object' ? this.form.attr.style : {}
            var attr = {...this.form.attr}
            delete attr.style
            delete attr.class
            if (attr.inputmode !== undefined){
                attr.inputMode = attr.inputmode
                delete attr.inputmode
            }

            if (column.formElements.includes('widget') && column.formElements.includes('errors')) {
                var errors = []
                if (this.form.errors.length > 0) {
                    column.wrapper.class = column.wrapper.class + ' errors'
                    errors = this.form.errors.map((content, errorKey) => {
                        return (<li key={errorKey}>{content}</li>)
                    })
                }

                if (this.form.block_prefixes.includes('ckeditor')){
                    return (<td className={column.class} key={columnKey}><div className={column.wrapper.class}>
                    <CKEditor editor={DocumentEditor} data={this.form.value} aria-describedby={this.form.id + '_help'} />
                        {this.form.errors.length > 0 ? <ul>{errors}</ul> : ''}
                    </div></td>)
                }

                return (<td className={column.class} key={columnKey}><div className={column.wrapper.class}>
                    <textarea className={this.form.attr.class} style={this.form.attr.style} id={this.form.id}
                              name={this.form.full_name} defaultValue={this.form.value} {...attr} aria-describedby={this.form.id + '_help'} />
                    {this.form.errors.length > 0 ? <ul>{errors}</ul> : ''}
                </div></td>)
            } else if (column.formElements.includes('widget')) {
                if (this.form.block_prefixes.includes('ckeditor')){
                    return (<td className={column.class} key={columnKey}><div className={column.wrapper.class}>
                        <CKEditor editor={DocumentEditor} data={this.form.value} aria-describedby={this.form.id + '_help'} />
                    </div></td>)
                }
                return (<td className={column.class} key={columnKey}><div className={column.wrapper.class}>
                    <textarea className={this.form.attr.class} style={this.form.attr.style} id={this.form.id}
                              name={this.form.full_name} defaultValue={this.form.value} {...attr} aria-describedby={this.form.id + '_help'} />
                        </div></td>)
            }

        })

        return (<tr className={this.row.class} style={this.row.style}>
            {columns}
        </tr> )
    }
}

FormTextArea.propTypes = {
    form: PropTypes.object.isRequired,
    row: PropTypes.object.isRequired,
}
