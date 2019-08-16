'use strict'

import React from "react"
import PropTypes from 'prop-types'

export default function FormErrors(props) {
    const {
        template,
        form,
        defaults,
    } = props

    if (form.errors.length === 0) {
        return null
    }


    console.log(template)
    console.log(form)
    console.log(defaults)

    alert(form.errors)
}

FormErrors.propTypes = {
    template: PropTypes.object.isRequired,
    form: PropTypes.object.isRequired,
    defaults: PropTypes.object.isRequired,
}
