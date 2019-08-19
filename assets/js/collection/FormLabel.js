'use strict'

import React from "react"
import PropTypes from 'prop-types'

export default function FormLabel(props) {
    const {
        template,
        formData,
        defaults,
        elementKey,
    } = props

    var help = ''
    var classValue = defaults.help.class
    if (formData.help !== null) {
        if (formData.help_attr.length > 0 && formData.help_attr.class !== undefined) {
            classValue = formData.help_attr.class
        }
        help = (<span className={classValue}><br/>{form.help}</span>)
    }
    classValue = defaults.label.class
    if (formData.label_attr.length > 0 && formData.attr.class !== undefined) {
        classValue = formData.attr.class
    }

    return (<label htmlFor={formData.id} className={classValue}>{formData.label}{formData.required ? ' *' : ''}{help}</label>);
}

FormLabel.propTypes = {
    template: PropTypes.object.isRequired,
    formData: PropTypes.object.isRequired,
    defaults: PropTypes.object.isRequired,
    elementKey: PropTypes.number.isRequired,
}
