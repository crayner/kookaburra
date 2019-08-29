'use strict'

import React from "react"
import PropTypes from 'prop-types'
import FormInput from "../FormInput"
import FormErrors from "../FormErrors"

export default function Standard(props) {
    const {
        form,
        functions,
    } = props

    return (<tr>
        <td>
            <FormLabel />
            <FormHelp />
        </td>
        <td>
            <div>
                <FormInput form={form} functions={functions}/>
                <FormErrors form={form}/>
            </div>
        </td>
    </tr>)
}

Standard.propTypes = {
    form: PropTypes.object.isRequired,
    functions: PropTypes.object.isRequired,
}