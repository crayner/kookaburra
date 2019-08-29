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

    return (<tr {...row_attr}>
        <td {...column_attr}>
            <h3>{form.label}</h3>
        </td>
    </tr>)

}

HeaderRow.propTypes = {
    form: PropTypes.object.isRequired,
    functions: PropTypes.object.isRequired,
    columns: PropTypes.number.isRequired,
}