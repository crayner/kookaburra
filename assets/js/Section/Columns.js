'use strict'

import React from "react"
import PropTypes from 'prop-types'

export default function Columns(props) {
    const {
        columns,
    } = props

    const content = Object.keys(columns).map(key => {
        const column = columns[key]

        if (null !== column.content) {
            return (
                <div className={column.class} id={column.id} style={column.style}>
                    {column.content}
                </div>
            )
        }
        return null
    })

    return (
        {content}
    )

}

Columns.propTypes = {
    columns: PropTypes.object.isRequired,
}
