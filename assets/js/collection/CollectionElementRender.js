'use strict'

import React from "react"
import PropTypes from 'prop-types'
import CollectionElementRow from "./CollectionElementRow"
import FormInput from "./FormInput"

export default function CollectionElementRender(props) {
    const {
        template,
        name,
        elementKey,
        formData,
    } = props

    const elements = Object.keys(formData).map(key => {
        var child = formData[key]
        var elementTemplate = getElementTemplate(child.block_prefixes, template)

        if (elementTemplate.type === 'hidden') {
            return <FormInput {...props} template={elementTemplate} defaults={template.defaults}
                             elementKey={elementKey} key={key} formData={child} />

        } else if (Object.keys(elementTemplate).length > 0) {
            return <CollectionElementRow {...props} template={elementTemplate} key={key}
                                         elementKey={elementKey} defaults={template['defaults']} formData={child} />
        }
        return ''
    })


    return (<div className={'collectionElement'} id={name}>{elements}</div>)
}

CollectionElementRender.propTypes = {
    template: PropTypes.object.isRequired,
    formData: PropTypes.object.isRequired,
    name: PropTypes.string.isRequired,
    elementKey: PropTypes.number.isRequired,
}

function getElementTemplate(prefixes, template){
    var prefix = prefixes[1]
    var subPrefix = prefixes[2]

    if (subPrefix === 'url' && prefix === 'text'){
        return template['input-url']
    }

    if (prefix === 'text') {
        return template['input-text'];
    }

    if (prefix === 'file') {
        return template['input-file'];
    }

    if (prefix === 'hidden'){
        return template['input-hidden']
    }

    if (prefix === 'choice'){
        return template['choice']
    }

    console.log(prefixes)
    return {};
}