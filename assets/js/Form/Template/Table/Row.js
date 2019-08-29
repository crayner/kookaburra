'use strict'

import React from "react"
import PropTypes from 'prop-types'
import HeaderRow from "./HeaderRow"
import SingleRow from "./SingleRow"
import Widget from "../../Widget"

export default function Row(props) {
    const {
        form,
        functions,
        columns,
    } = props

    form.columns = columns
    if (form.type === 'hidden' && form.row_style !== 'hidden') form.row_style = 'hidden'

    if (form.row_style === 'header') {
        return (<HeaderRow form={form} functions={functions} columns={columns}/>)
    }

    if (form.row_style === 'single') {
        return (<SingleRow form={form} functions={functions} columns={columns}/>)
    }

    if (form.row_style === 'hidden') {
        return (<tr style={{display: 'none'}}><td><Widget form={form} functions={functions} /></td></tr>)
    }

    console.log(form)
    return (<tr><td> Form Row {form.row_style}</td></tr>)

}

Row.propTypes = {
    form: PropTypes.object.isRequired,
    functions: PropTypes.object.isRequired,
    columns: PropTypes.number.isRequired,
}