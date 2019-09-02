'use strict'

import React from "react"
import PropTypes from 'prop-types'
import LabelHelp from "../../LabelHelp"
import Widget from "../../Widget"

export default function Standard(props) {
    const {
        form,
        functions,
    } = props

    return (<tr className={'flex flex-col sm:flex-row justify-between content-center p-0'}>
        <td className={'flex flex-col flex-grow justify-center -mb-1 sm:mb-0  px-2 border-b-0 sm:border-b border-t-0'}>
            <LabelHelp form={form}/>
        </td>
        <td className={'w-full max-w-full sm:max-w-xs flex justify-end items-center px-2 border-b-0 sm:border-b border-t-0'}>
            <Widget form={form} functions={functions} />
        </td>
    </tr>)
}

Standard.propTypes = {
    form: PropTypes.object.isRequired,
    functions: PropTypes.object.isRequired,
}