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
        prototype = {}
        let x = 0
        Object.keys(form.children).map(key => {
            if (x === 0)
                prototype = form.children[key]
            x++
        })

        Object.keys(prototype.children).map(key => {
            let child = prototype.children[key]

            if (child.type !== 'hidden')
                columnCount++
        })

    } else {
        Object.keys(prototype.children).map(key => {
            const child = prototype.children[key]
            if (child.type !== 'hidden') {
                columnCount++
            }
        })
    }

    if (form.allow_add || form.allow_delete)
        columnCount++

    return (<CollectionRows form={form} functions={functions} columnCount={columnCount} key={form.collection_key} />)
}

CollectionApp.propTypes = {
    form: PropTypes.object.isRequired,
    functions: PropTypes.object.isRequired,
}

