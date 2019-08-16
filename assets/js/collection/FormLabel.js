'use strict'

import React from "react"
import PropTypes from 'prop-types'

export default function FormLabel(props) {
    const {
        template,
        form,
        defaults,
    } = props

    var help = ''
    var classValue = defaults.help.class
    if (form.help !== '') {
        if (form.help_attr.length > 0 && form.help_attr.class !== 'undefined') {
            classValue = form.help_attr.class
        }
        help = (<span className={classValue}><br/>{form.help}</span>)
    }
    classValue = defaults.label.class
    if (form.attr.length > 0 && form.attr.class !== 'undefined') {
        classValue = form.attr.class
    }

    return (<label htmlFor={form.id} className={classValue}>{form.label}{form.required ? ' *' : ''}{help}</label>);
}

FormLabel.propTypes = {
    template: PropTypes.object.isRequired,
    form: PropTypes.object.isRequired,
    defaults: PropTypes.object.isRequired,
}
