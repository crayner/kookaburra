'use strict'

import React from "react"
import PropTypes from 'prop-types'
import {rowAttr, columnAttr} from '../../buildAttr'

export default function HeaderRow(props) {
    const {
        form,
        functions,
        columns,
    } = props

    let row_attr = rowAttr(form, 'break flex flex-col sm:flex-row justify-between content-center p-0')
    let column_attr = columnAttr(form, 'flex-grow justify-center px-2 border-b-0 sm:border-b border-t-0')
    if (columns > 1) {
        column_attr.colSpan = columns
    }
    let label = (<h3>{form.label}</h3>)
    if (form.header_type === 'h4')
        label = (<h4>{form.label}</h4>)

    return (<tr {...row_attr}>
        <td {...column_attr}>
            {label}
        </td>
    </tr>)

}

HeaderRow.propTypes = {
    form: PropTypes.object.isRequired,
    functions: PropTypes.object.isRequired,
    columns: PropTypes.number.isRequired,
}