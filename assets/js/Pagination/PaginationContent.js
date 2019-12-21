'use strict'

import React from "react"
import PropTypes from 'prop-types'
import Img from 'react-image'
import Parser from "react-html-parser"

export default function PaginationContent(props) {
    const {
        row,
        content,
        functions,
    } = props

    if (content.length === 0)
    {
        return (
            <tbody>
                <tr>
                    <td colSpan={row.columns.length + 1}>
                        <div className="h-48 rounded-sm border bg-gray-100 shadow-inner overflow-hidden">
                            <div className="w-full h-full flex flex-col items-center justify-center text-gray-600 text-lg">
                                {row.emptyContent}
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>)
    }

    let rows = Object.keys(content).map(rowKey => {
        const rowContent = content[rowKey]

        let columns = []
        Object.keys(row.columns).map(columnKey => {
            let columnDefinition = row.columns[columnKey]
            if (columnDefinition.dataOnly)
                return
            let columnContent = []
            if (typeof columnDefinition.contentKey === 'object')
            {
                if (columnDefinition.contentType === 'image') {
                    let style = typeof columnDefinition.options['style'] === 'undefined' ? {} : columnDefinition.options['style']
                    let className = typeof columnDefinition.options['class'] === 'undefined' ? '' : columnDefinition.options['class']
                    columnDefinition.contentKey.map((value, key) => {
                        if (key === 0)
                            columnContent.push(<Img src={rowContent[value]} style={style} className={className}
                                                    key={key}/>)
                    })
                } else if (columnDefinition.contentType === 'link') {
                    let link = typeof columnDefinition.options['link'] === 'undefined' ? '#' : columnDefinition.options['link']
                    let title = typeof columnDefinition.options['title'] === 'undefined' ? '' : columnDefinition.options['title']
                    let style = typeof columnDefinition.options['style'] === 'undefined' ? {} : columnDefinition.options['style']
                    let className = typeof columnDefinition.options['class'] === 'undefined' ? '' : columnDefinition.options['class']

                    if (typeof columnDefinition.options['route_options'] === 'object') {
                        Object.keys(columnDefinition.options['route_options']).map(x => {
                            const search = columnDefinition.options['route_options'][x]
                            link = link.replace('__' + search + '__', rowContent[search])
                        })
                    }

                    columnDefinition.contentKey.map((value, key) => {
                        if (key === 0)
                            if (className === '')
                                columnContent.push(<a href={link} title={title} style={style} key={key}>{Parser(rowContent[value])}</a>)
                            else
                                columnContent.push(<a href={link} title={title} className={className} style={style} key={key}>{Parser(rowContent[value])}</a>)
                    })
                } else {
                    columnDefinition.contentKey.map((value, key) => {
                        if (key > 0)
                            columnContent.push(<span key={key}
                                                     className={'small text-gray-600 italic'}><br/>{Parser(rowContent[value])}</span>)
                        else
                            columnContent.push(<span key={key}>{Parser(rowContent[value])}</span>)
                    })
                }
            } else {
                columnContent = [rowContent[columnDefinition.contentKey]]
            }

            columns.push(<td key={columnKey} className={columnDefinition.class}>{columnContent}</td> )
        })

        // add Actions column
        let actions = Object.keys(row.actions).map(actionKey => {
            let action = row.actions[actionKey]
            if (action.displayWhen === '' || rowContent[action.displayWhen]) {
                if (action.onClick === '')
                    return (<a href={rowContent.actions[actionKey]} className={action.aClass} key={actionKey}
                               title={action.title}><span className={action.spanClass}></span></a>)

                return (<a onClick={() => functions[action.onClick](rowContent.actions[actionKey],rowContent)}
                           className={action.aClass} key={actionKey} title={action.title}><span
                    className={action.spanClass}></span></a>)
            }
        })
        if (row.actions.length > 0) {
            columns.push(<td key={'actions'}>
                <div
                    className="hidden group-hover:flex sm:flex absolute sm:static top-0 right-0 -mr-1 rounded shadow sm:shadow-none bg-white sm:bg-transparent px-1 -mt-3 sm:m-0 sm:p-0 z-10">
                    {actions}
                </div>
            </td>)
        }

        return (<tr className={actions.columnClass} key={rowKey} id={'pagination' + rowContent.id}>{columns}</tr>)

    })

    return (<tbody>
        {rows}</tbody>)
}


PaginationContent.propTypes = {
    row: PropTypes.object.isRequired,
    content: PropTypes.array.isRequired,
    functions: PropTypes.object.isRequired,
}
