'use strict'

import React from "react"
import PropTypes from 'prop-types'
import FormLabel from "./FormLabel"
import FormErrors from "./FormErrors"
import FormWidget from "./FormWidget"

export default function CollectionElementRow(props) {
    const {
        template,
        value,
        form,
        defaults,
        elementKey,
    } = props

    var columns = ''

    if (template.style === 'row' && template.columns.length > 0)
    {
        columns = template.columns.map((column,key) => {
            var content = column.content.map((style, key) => {
                if (style === 'label') {
                    return <FormLabel template={template} defaults={defaults} form={form} key={key} />
                } else if (style === 'errors') {
                    return <FormErrors template={template} form={form} defaults={defaults} key={key} />
                } else if (style === 'widget') {
                    return <FormWidget template={template} form={form} defaults={defaults} elementKey={elementKey} key={key} value={value}/>
                }
                return null
            })

            return (<div className={column.class} key={key}>{content}</div>)
        })
    }

    if (template.style === 'row')
        return (<div className={template.row.class}>
            {columns}
        </div>)

    return null
}

CollectionElementRow.propTypes = {
    template: PropTypes.object.isRequired,
    form: PropTypes.object.isRequired,
    defaults: PropTypes.object.isRequired,
}
