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
        formData,
    } = props

    var columns = ''

    if (template.style === 'row' && template.columns.length > 0)
    {
        columns = template.columns.map((column,key) => {
            var content = column.content.map((style, key) => {
                if (style === 'label') {
                    return <FormLabel template={template} defaults={defaults} formData={formData} key={key} elementKey={elementKey} />
                } else if (style === 'errors') {
                    return <FormErrors template={template} form={form} defaults={defaults} key={key} />
                } else if (style === 'widget') {
                    return <FormWidget {...props} template={template} form={form} defaults={defaults} elementKey={elementKey} key={key} value={value} formData={formData} />
                }
                return null
            })

            return (<div className={column.class} key={key}>{content}</div>)
        })
    }

    if (template.style === 'widget') {
        columns = (<FormWidget {...props} template={template} form={form} defaults={defaults} elementKey={elementKey} value={value} formData={formData} />)
    }

    if (form.errors.length > 0){
        template.row.class = template.row.class + ' errors'
    }

    var rowClass = template.row.class;

    if (formData.row_class !== null) {
        rowClass = formData.row_class
    }
    if (template.style === 'row')
        return (<div className={'collectionRow ' + rowClass.replace('__name__', elementKey)} id={'row_' + form.id.replace('__name__', elementKey)}>
            {columns}
        </div>)

    return null
}

CollectionElementRow.propTypes = {
    template: PropTypes.object.isRequired,
    form: PropTypes.object.isRequired,
    defaults: PropTypes.object.isRequired,
    formData: PropTypes.object.isRequired,
}
