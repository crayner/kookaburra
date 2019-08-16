'use strict'

import React from "react"
import PropTypes from 'prop-types'
import CollectionElementRow from "./CollectionElementRow"

export default function CollectionElementRender(props) {
    const {
        template,
        values,
        children,
        name,
    } = props

    const elements = children.map((child,key) => {
        var elementTemplate = getElementTemplate(child.block_prefixes, template)
        var elementValue = values[child.name]

        if (elementTemplate.length !== {})
            return <CollectionElementRow template={elementTemplate} value={elementValue} key={key} elementKey={key} form={child} defaults={template['defaults']}  />
        return ''
    })


    return (<div className={'collectionElement'} id={name}>{elements}</div>)
}

CollectionElementRender.propTypes = {
    template: PropTypes.object.isRequired,
    values: PropTypes.object.isRequired,
    children: PropTypes.array.isRequired,
    name: PropTypes.string.isRequired,
}

function getElementTemplate(prefixes, template){
    var prefix = prefixes[1]
    if (prefix === 'text') {
        return template['input-text'];
    }
    console.log(prefixes)
    return {};
}