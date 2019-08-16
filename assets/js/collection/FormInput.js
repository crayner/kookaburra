'use strict'

import React from "react"
import PropTypes from 'prop-types'

export default function FormInput(props) {
    const {
        template,
        form,
        value,
        defaults,
        elementKey,
    } = props

    var classValue = defaults.input.class
    if (form.attr.length > 0 && form.attr.class !== 'undefined') {
        classValue = form.attr.class
    }

    var classValueWrapper = defaults.wrapper.class
    if (template.wrapper !== 'undefined' && template.wrapper.class !== 'undefined') {
        classValueWrapper = template.wrapper.class
    }

    return (
        <div className={classValueWrapper}>
            <input type={template.type} className={classValue} defaultValue={value} id={form.id.replace('__name__', elementKey)} name={form.full_name.replace('__name__', elementKey)} />
        </div>
    )
}

FormInput.propTypes = {
    template: PropTypes.object.isRequired,
    form: PropTypes.object.isRequired,
    defaults: PropTypes.object.isRequired,
    elementKey: PropTypes.number.isRequired,
}
