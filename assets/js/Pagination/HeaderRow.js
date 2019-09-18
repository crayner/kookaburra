'use strict'

import React from "react"
import PropTypes from 'prop-types'

export default function HeaderRow(props) {
    const {
        row,
        sortColumn,
        sortColumnName,
        sortColumnDirection,
    } = props

    let columns = Object.keys(row.columns).map(columnKey => {
        const column = row.columns[columnKey]
        const help = column.help !== null ? (<span className={'small text-gray-600'}><br/>{content.help}</span>) : ''
        let sort = column.sort === true ? (<span className={'fas fa-sort fa-fw text-gray-600'} style={{float: 'right'}}></span>) : ''
        if (sortColumnName === column.contentKey) {
            sort = (<span className={'fas fa-sort-' + sortColumnDirection + ' fa-fw text-gray-800'} style={{float: 'right'}}></span>)
        }
        return (<th className={column.class} key={columnKey} onClick={() => sortColumn(column.contentKey)}>{sort}{column.label}{help}</th>)
    })

    columns.push(<th className={'column width1'} key={'actions'}>{row.actionTitle}</th>)

    return (<thead><tr className={'head text-xs'}>{columns}</tr></thead>)
}


HeaderRow.propTypes = {
    row: PropTypes.object.isRequired,
    sortColumn: PropTypes.func.isRequired,
    sortColumnName: PropTypes.string.isRequired,
    sortColumnDirection: PropTypes.string.isRequired,
}