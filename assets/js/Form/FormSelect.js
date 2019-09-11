'use strict'

import React from "react"
import PropTypes from 'prop-types'
import {isEmpty} from "../component/isEmpty"

export default function FormSelect(props) {
    const {
        form,
        wrapper_attr,
        widget_attr,
    } = props

    var options = []

    if (typeof form.placeholder !== 'undefined' && form.placeholder !== false){
        options.push(<option key={'placeholder'} className={'text-gray-500'}>{form.placeholder}</option>)
    }

    Object.keys(form.choices).map(choice => {
        if (typeof form.choices[choice].choices === 'undefined') {
            options.push(<option value={form.choices[choice].value}
                                 key={form.choices[choice].value}>{form.choices[choice].label}</option>)
        } else {
            const groupName = form.choices[choice].label
            let subOptions = []
            Object.keys(form.choices[choice].choices).map(subChoice => {
                subOptions.push(<option value={form.choices[choice].choices[subChoice].value}
                                     key={form.choices[choice].choices[subChoice].value}>{form.choices[choice].choices[subChoice].label}</option>)
            })
            options.push(<optgroup key={groupName} label={groupName}>{subOptions}</optgroup>)
        }
    })

    return (
        <div {...wrapper_attr}>
            <select multiple={form.multiple} {...widget_attr} defaultValue={form.value}>
                {options}
            </select>
        </div>
    )
}

FormSelect.propTypes = {
    form: PropTypes.object.isRequired,
    wrapper_attr: PropTypes.object.isRequired,
    widget_attr: PropTypes.object.isRequired,
}