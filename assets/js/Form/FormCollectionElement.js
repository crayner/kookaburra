'use strict'

import React from "react"
import PropTypes from 'prop-types'
import FormWidgetErrors from "./FormWidgetErrors"
import FormInput from "./FormInput"
import {isEmpty} from "../component/isEmpty"

export default function FormCollectionElement(props) {
    const {
        element,
        template,
        functions,
    } = props

    let actions = []

    let columns = Object.keys(element.children).map(childName => {
        let child = element.children[childName]

        if (isEmpty(child.column)) {
            child.column = {}
        }
        if (isEmpty(child.column.wrapper))
        {
            child.column.wrapper = template.element.wrapper
        }

        if (child.block_prefixes.includes('hidden'))
        {
            actions.push(<FormInput form={{...child}} functions={functions} key={childName} />)
            return null
        }

        return (
            <td key={childName}>
                <FormWidgetErrors form={child} column={template.element} functions={functions} />
            </td>
        )
    })

    if (template.allow_delete) {
        actions.push(<button type={'button'} title={'Delete Element'} className={'button text-gray-800'} key={'deleteButton'} onClick={() => functions.deleteElement(element)}><span className={'fas fa-trash fa-fw'}></span></button>)
    }

    if (actions.length > 0) {
        columns.push(<td className={template.actions.class} key={'actions'}>{actions}</td>)
    }

    return (<tr id={element.id}>
        {columns}
    </tr>)
}

FormCollectionElement.propTypes = {
    element: PropTypes.object.isRequired,
    template: PropTypes.object.isRequired,
    functions: PropTypes.object.isRequired,
}