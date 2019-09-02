'use strict'

import React from "react"
import PropTypes from 'prop-types'
import HeaderRow from "./HeaderRow"
import ParagraphRow from "./ParagraphRow"
import SingleRow from "./SingleRow"
import Widget from "../../Widget"
import Standard from "./Standard"

export default function Row(props) {
    const {
        form,
        functions,
        columns,
    } = props

    form.columns = columns
    if (form.type === 'hidden' && form.row_style !== 'hidden') form.row_style = 'hidden'

    if (form.type === 'header') {
        return (<HeaderRow form={form} functions={functions} columns={columns}/>)
    }

    if (form.type === 'paragraph') {
        return (<ParagraphRow form={form} functions={functions} columns={columns}/>)
    }

    if (form.row_style === 'single') {
        return (<SingleRow form={form} functions={functions} columns={columns}/>)
    }

    if (form.row_style === 'hidden') {
        return (<tr style={{display: 'none'}}><td><Widget form={form} functions={functions} /></td></tr>)
    }

    if (form.row_style === 'standard') {
        return (<Standard form={form} functions={functions} />)
    }

    if (form.row_style === 'transparent' || form.row_style === 'repeated')
    {
        return Object.keys(form.children).map(childKey => {
            let child = form.children[childKey]
            if (child.type === 'password_generator' && childKey === 'second') {
                child.type = 'password'
            }
            return (<Row form={child} key={child.name} functions={functions} columns={columns} />)
        })
    }

    console.log(form)
    return (<tr><td> Form Row {form.row_style}</td></tr>)

}

Row.propTypes = {
    form: PropTypes.object.isRequired,
    functions: PropTypes.object.isRequired,
    columns: PropTypes.number.isRequired,
}