'use strict'

import React from 'react'
import PropTypes from 'prop-types'
import CollectionRows from "./Template/Table/CollectionRows"

export default function CollectionApp(props) {
    const {
        functions,
        form,
    } = props

    let columnCount = 0
    let prototype = {...form.prototype}
    if (Object.keys(prototype).length === 0) {
        columnCount = Object.keys(form.children).length
    } else {
        Object.keys(prototype.children).map(key => {
            const child = prototype.children[key]
            if (child.type !== 'hidden') {
                columnCount++
            }
        })
    }
    columnCount++

    return (<CollectionRows form={form} functions={functions} columnCount={columnCount} key={form.collection_key} />)
}

CollectionApp.propTypes = {
    form: PropTypes.object.isRequired,
    functions: PropTypes.object.isRequired,
}

