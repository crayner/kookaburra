'use strict'

import React from "react"
import PropTypes from 'prop-types'
import CollectionElementRender from "./CollectionElementRender"

export default function CollectionRender(props) {
    const {
        template,
        form,
    } = props

    if (!form.block_prefixes.includes('react_collection'))
    {
        return (
            <div className={'error'}>The form handed to the Collection Render class is not a React Collection.</div>
        )
    }
    if (form.value.length === 0) {
        return (
            <div className={'error'}><p>The form handed to the Collection Render does not contain any content.</p></div>
        )
    }

    const elements = form.value.map((value,key) => {

        return (<CollectionElementRender key={key} template={template} values={value} children={form.prototype.children} name={form.id + '_' + key} />)
    })


        return (<div className={'collectionList'}>{elements}</div>)
}

CollectionRender.propTypes = {
    template: PropTypes.object.isRequired,
    form: PropTypes.object.isRequired,
}