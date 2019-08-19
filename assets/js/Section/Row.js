'use strict'

import React from "react"
import PropTypes from 'prop-types'
import Columns from "./Columns"

export default function Row(props) {
    const {
        row,
    } = props

    return (
        <div className={row.class} style={row.style} id={row.id}><Columns columns={row.columns} /></div>
    )

}

Row.propTypes = {
    row: PropTypes.object.isRequired,
}
