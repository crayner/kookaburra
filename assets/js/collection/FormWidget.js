'use strict'

import React from "react"
import PropTypes from 'prop-types'
import FormInput from "./FormInput"
import FormSelect from "./FormSelect"

export default function FormWidget(props) {
    const {
        template,
    } = props

    if (template.element === 'input') {
        return <FormInput {...props} template={template} />
    }

    if (template.element === 'select') {
        return <FormSelect {...props} template={template} />
    }

    return null
}

FormWidget.propTypes = {
    template: PropTypes.object.isRequired,
}
