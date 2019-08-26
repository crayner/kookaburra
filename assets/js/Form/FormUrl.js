'use strict'

import React from "react"
import PropTypes from 'prop-types'
import {isEmpty} from "../component/isEmpty"
import Parser from "html-react-parser"
import FormToggle from "./FormToggle"
import {openPage} from "../component/openPage"

export default function FormUrl(props) {
    const {
        column,
        form,
        functions,
    } = props

    var row = form.row !== undefined ? form.row : {}
    row.style = row.style !== undefined && typeof(row.style) === 'object' ? row.style : {}

    if (column.formElements.includes('label_help')) {
        return (<label htmlFor={form.id} className={column.label.class} style={column.style}>{form.label}{isEmpty(form.help) ? '' : (<span className={column.help.class} id={form.id + '_help'}><br/>{form.help_html ? Parser(form.help) :form.help}</span>)}</label>)
    }

    if (column.formElements.includes('help')) {
        return (<span className={column.help.class} id={form.id + '_help'}>{form.help_html ? Parser(form.help) :form.help}</span>)
    }

    form.attr.class = form.attr.class !== undefined && typeof(form.attr.class) === 'string' ? form.attr.class : ''
    form.attr.style = form.attr.style !== undefined && typeof(form.attr.style) === 'object' ? form.attr.style : {}
    var widget_attr = {...form.attr}
    delete widget_attr.style
    delete widget_attr.class
    if (widget_attr.inputmode !== undefined){
        widget_attr.inputMode = widget_attr.inputmode
        delete widget_attr.inputmode
    }

    if (form.on_change !== null) {
        let onChange = functions[form.on_change]
        widget_attr.onChange = (e) => onChange(e)
    }


    if (column.formElements.includes('widget') && column.formElements.includes('errors')) {
        var errors = []
        if (form.errors.length > 0) {
            column.wrapper.class = column.wrapper.class + ' errors'
            errors = form.errors.map((content,key) => {
                return (<li key={key}>{content}</li>)
            })
        }

        return (<div className={column.wrapper.class}>
            <input type={form.type} className={form.attr.class} style={form.attr.style} defaultValue={form.value} id={form.id}
                   name={form.full_name} {...widget_attr} aria-describedby={form.id + '_help'} />
            {form.value !== '' ? <button type={'button'} title={row.title} className={row.button.class} onClick={() => openUrl(form.value)}><span className={'fa-fw fas fa-external-link-alt'}></span></button> : ''}
            {form.errors.length > 0 ? <ul>{errors}</ul> : ''}
        </div>)
    } else if (column.formElements.includes('widget')) {
        return (<div className={column.wrapper.class}>
            <input type={form.type} className={form.attr.class} style={form.attr.style} defaultValue={form.value} id={form.id}
                   name={form.full_name} {...widget_attr} aria-describedby={form.id + '_help'} />
            {form.value !== '' ? <button type={'button'} title={row.title} className={row.button.class} onClick={() => openUrl(form.value)}><span className={'fa-fw fas fa-external-link-alt'}></span></button> : ''}
        </div>)
    }

    return ('url')

    function openUrl(file){

        window.open(file, '_blank')
    }
}

FormUrl.propTypes = {
    form: PropTypes.object.isRequired,
    column: PropTypes.object.isRequired,
    functions: PropTypes.object.isRequired,
}