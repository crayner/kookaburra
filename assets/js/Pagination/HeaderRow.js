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
        const help = column.help !== null ? (<span className={'small text-gray-600 italic'}><br/>{column.help}</span>) : ''
        let sort = column.sort === true ? (<span className={'fas fa-sort fa-fw text-gray-600'} style={{float: 'right'}}></span>) : ''
        let w = sortColumnName
        if (typeof sortColumnName === 'array')
            w = sortColumnName[0]
        if (w === column.contentKey) {
            sort = (<span className={'fas fa-sort-' + sortColumnDirection + ' fa-fw text-gray-800'} style={{float: 'right'}}></span>)
        }

        let headerClass = column.headerClass !== '' ? column.headerClass : column.class

        return (<th className={headerClass} key={columnKey} onClick={() => sortColumn(column.contentKey)}>{sort}{column.label}{help}</th>)
    })

    if (row.actions.length > 0) {
        columns.push(<th className={'column width1'} key={'actions'}>{row.actionTitle}</th>)
    }
    return (<thead><tr className={'head text-xs'}>{columns}</tr></thead>)
}


HeaderRow.propTypes = {
    row: PropTypes.object.isRequired,
    sortColumn: PropTypes.func.isRequired,
    sortColumnName: PropTypes.oneOfType([
        PropTypes.string,
        PropTypes.array,
    ]).isRequired,
    sortColumnDirection: PropTypes.string.isRequired,
}
