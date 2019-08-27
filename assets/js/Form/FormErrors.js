'use strict'

import React from "react"
import PropTypes from 'prop-types'

export default function FormErrors(props) {
    const {
        form,
    } = props


    let errors = []
    if (form.errors.length > 0) {
        errors = form.errors.map((content,key) => {
            return (<li key={key}>{content}</li>)
        })
    }

    if (errors.length > 0) {
        return (<ul>{errors}</ul>)
    }
    return null

}

FormErrors.propTypes = {
    form: PropTypes.object.isRequired,
}