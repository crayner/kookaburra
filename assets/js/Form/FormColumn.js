'use strict'

import React from "react"
import PropTypes from 'prop-types'
import {isEmpty} from "../component/isEmpty"
import Parser from "html-react-parser"
import FormToggle from "./FormToggle"

export default function FormColumn(props) {
    const {
        column,
        form,
        functions,
    } = props

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

    if (form.on_change !== null){
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

        if (form.block_prefixes.includes('choice')) {
            var options = []
            if (!isEmpty(form.placeholder)){
                options.push(<option key={0} className={'text-gray-500'} value={''}>{form.placeholder}</option>)
            }

            Object.keys(form.choices).map(choice => {
                const label = form.choices[choice].label
                const value = form.choices[choice].value
                options.push(<option value={value} key={value}>{label}</option>)
            })

            return (<div className={column.wrapper.class}>
                <select className={form.attr.class} style={form.attr.style} id={form.id} name={form.full_name} defaultValue={form.value} multiple={form.multiple} {...widget_attr} aria-describedby={form.id + '_help'}>
                    {options}
                </select>
                {form.errors.length > 0 ? <ul>{errors}</ul> : ''}
            </div>)
        }

        if (form.block_prefixes.includes('toggle')) {
            return (<FormToggle form={form} column={column} errors={errors} />)
        }

        return (<div className={column.wrapper.class}>
            <input type={form.type} className={form.attr.class} style={form.attr.style} id={form.id}
                   name={form.full_name} defaultValue={form.value} {...widget_attr} aria-describedby={form.id + '_help'} />
            {form.errors.length > 0 ? <ul>{errors}</ul> : ''}
        </div>)
    } else if (column.formElements.includes('widget')) {
        return (<div className={column.wrapper.class}>
            <input type={form.type} className={form.attr.class} style={form.attr.style} id={form.id}
                   name={form.full_name} defaultValue={form.value} {...widget_attr} aria-describedby={form.id + '_help'} />
        </div>)
    }

    return ('column')
}

FormColumn.propTypes = {
    form: PropTypes.object.isRequired,
    column: PropTypes.object.isRequired,
    functions: PropTypes.object.isRequired,
}