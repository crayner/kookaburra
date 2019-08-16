'use strict'

import React from "react"
import PropTypes from 'prop-types'
import FormInput from "./FormInput"

export default function FormWidget(props) {
    const {
        template,
    } = props

    if (template.element === 'input') {
        return <FormInput {...props} template={template} />
    }

    return null
}

FormWidget.propTypes = {
    template: PropTypes.object.isRequired,
}
