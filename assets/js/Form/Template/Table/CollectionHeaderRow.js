'use strict'

import React from "react"
import PropTypes from 'prop-types'

export default function CollectionHeaderRow(props) {
    const {
        form,
        functions
    } = props

    if (form.header_row === false) return (null)
    let prototype = {...form.prototype}
    if (form.header_row === true) {
        let elements = Object.keys(prototype.children).map(childKey => {
            let child = prototype.children[childKey]
            if (child.type !== 'hidden') {
                if (typeof child.help === 'string')
                    return <th className={'text-xxs sm:text-xs p-2 sm:py-3'} key={child.name}>{child.label}<br/><span className={'text-gray-500 xs emphasis'}>{child.help}</span></th>
                return <th className={'text-xxs sm:text-xs p-2 sm:py-3'} key={child.name}>{child.label}</th>
            }
        })
        elements.push(<th className={'shortWidth text-xxs sm:text-xs p-2 sm:py-3 text-center'} key={'actions'}>{functions.translate('Actions')}</th>)
        return (
            <thead>
                <tr>{elements}</tr>
            </thead>
        )
    }
    if (typeof form.header_row === 'array') {
        let elements = form.header_row.map((child,childKey) => {
            if (typeof child.help === 'string')
                return <th {...child.attr} key={child.name}>{child.label}<br/><span className={'text-gray-500 small'}>{child.help}</span></th>
            return <th {...child.attr} key={child.name}>{child.label}</th>
        })
        return (
            <thead>
                <tr>{elements}</tr>
            </thead>
        )
    }
}

CollectionHeaderRow.propTypes = {
    form: PropTypes.object.isRequired,
    functions: PropTypes.object.isRequired,
}