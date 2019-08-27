'use strict'

import React from "react"
import PropTypes from 'prop-types'
import {isEmpty} from "../component/isEmpty"

export default function FormSelect(props) {
    const {
        form,
        functions,
    } = props


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

    var options = []
    if (!isEmpty(form.placeholder)){
        options.push(<option key={0} className={'text-gray-500'} value={''}>{form.placeholder}</option>)
    }

    Object.keys(form.choices).map(choice => {
        const label = form.choices[choice].label
        const value = form.choices[choice].value
        options.push(<option value={value} key={value}>{label}</option>)
    })

    if (typeof form.wrapper === 'undefined' || typeof form.wrapper.class === 'undefined') {
        return (<select className={form.attr.class} style={form.attr.style} id={form.id} name={form.full_name} defaultValue={form.value} multiple={form.multiple} {...widget_attr} aria-describedby={form.id + '_help'}>
            {options}
        </select>)
    }

    return (<div className={this.column.wrapper.class}>
        <select className={form.attr.class} style={form.attr.style} id={form.id} name={form.full_name} defaultValue={form.value} multiple={form.multiple} {...widget_attr} aria-describedby={form.id + '_help'}>
            {options}
        </select>
    </div>)
}

FormSelect.propTypes = {
    form: PropTypes.object.isRequired,
    functions: PropTypes.object.isRequired,
}