'use strict'

import React from "react"
import PropTypes from 'prop-types'

export default function PaginationContent(props) {
    const {
        row,
        content,
        functions,
    } = props



    if (content.length === 0)
    {
        return (
            <tbody><tr><td colSpan={row.columns.length + 1}>
            <div className="h-48 rounded-sm border bg-gray-100 shadow-inner overflow-hidden">
            < div className="w-full h-full flex flex-col items-center justify-center text-gray-600 text-lg">
                {row.emptyContent}
            </div>
            </div>></td></tr></tbody>)
    }

    let rows = Object.keys(content).map(rowKey => {
        const rowContent = content[rowKey]

        let columns = Object.keys(row.columns).map(columnKey => {
            let columnDefinition = row.columns[columnKey]
            let columnContent = []

            if (typeof columnDefinition.contentKey === 'object')
            {
                columnDefinition.contentKey.map((value,key) => {
                    if (key > 0)
                        columnContent.push(<span key={key} className={'small text-gray-600 italic'}><br/>{rowContent[value]}</span>)
                    else
                        columnContent.push(<span key={key}>{rowContent[value]}</span>)
                })
            } else {
                columnContent = [rowContent[columnDefinition.contentKey]]
            }

            return (<td key={columnKey} className={columnDefinition.class}>{columnContent}</td> )

        })

        // add Actions column
        let actions = Object.keys(row.actions).map(actionKey => {
            let action = row.actions[actionKey]
            if (action.onClick === '')
                return (<a href={rowContent.actions[actionKey]} className={action.aClass} key={actionKey} title={action.title}><span className={action.spanClass}></span></a> )

            return (<a href='#' onClick={() => functions[action.onClick](rowContent.actions[actionKey])} className={action.aClass} key={actionKey} title={action.title}><span className={action.spanClass}></span></a> )

        })

        if (row.actions.length > 0) {
            columns.push(<td key={'actions'}>
                <div
                    className=" hidden group-hover:flex sm:flex absolute sm:static top-0 right-0 -mr-1 rounded shadow sm:shadow-none bg-white sm:bg-transparent px-1 -mt-3 sm:m-0 sm:p-0 z-10">
                    {actions}
                </div>
            </td>)
        }

        return (<tr className={actions.columnClass} key={rowKey}>{columns}</tr>)

    })

    return (<tbody>
        {rows}</tbody>)
}


PaginationContent.propTypes = {
    row: PropTypes.object.isRequired,
    content: PropTypes.array.isRequired,
    functions: PropTypes.object.isRequired,
}
