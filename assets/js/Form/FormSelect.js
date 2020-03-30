'use strict'

import React from "react"
import PropTypes from 'prop-types'

export default function FormSelect(props) {
    const {
        form,
        wrapper_attr,
        widget_attr,
        errors,
        functions,
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

    let buttons = []
    if (form.auto_refresh)  {
        buttons.push(<button type="button" title={functions.translate('Refresh List')} key={'refresh'}
                             className="close-button grey" onClick={() => functions.refreshChoiceList(form)}><span className={'fas fa-sync fa-fw'} /></button>)
        if (form.add_url !== null)
            buttons.push(<a title={functions.translate('Add Element to List')} key={'add'} onClick={() => functions.addElementToChoice(form)}
                                 className="add-button" style={{marginRight: '32px'}}><span className={'fas fa-plus fa-fw'} /></a>)
    }

    return (
        <div {...wrapper_attr}>
            <select multiple={form.multiple} {...widget_attr} value={form.value} data-value={form.value}>
                {options}
            </select>
            {buttons}
            {errors}
        </div>
    )
}

FormSelect.propTypes = {
    form: PropTypes.object.isRequired,
    wrapper_attr: PropTypes.object.isRequired,
    widget_attr: PropTypes.object.isRequired,
    errors: PropTypes.array,
    functions: PropTypes.object.isRequired,
}

FormSelect.defaultProps = {
    errors: [],
}