'use strict';

import React from "react"
import PropTypes from 'prop-types'
import Parser from "react-html-parser"

export default function Message(props) {
    const {
        message,
        cancelMessage,
        translate,
    } = props

    if (typeof message.message === 'string') {
        return (
            <div className={message.class}>
                {Parser(message.message)}
                {message.close ?
                <button className={'button close ' + message.class} onClick={() => cancelMessage(message.id)}
                        title={translate('Close Message')} type='button'>
                    <span className={'fas fa-times-circle fa-fw ' + message.class}></span>
                </button>
                    : '' }
            </div>
        )
    }

    if (typeof message.message === 'object') {
        message.message.stack = message.message.stack.replace('<anonymous>', '&lt;anonymous&gt;')
        return (
            <div className={message.class}>
                {Parser(message.message.message)}<br/><span className={'small'}>{Parser(message.message.stack)}</span>
                {message.close ?
                <button className={'button close ' + message.class} onClick={() => cancelMessage(message.id)}
                        title={translate('Close Message')} type='button'>
                    <span className={'fas fa-times-circle fa-fw ' + message.class}></span>
                </button>
                    : '' }
            </div>
        )
    }

    if (typeof message.errors !== 'undefined')
        return null

    console.log('message.message is a ' + typeof message.message)
    console.log(message)
    return null
}

Message.propTypes = {
    message: PropTypes.object.isRequired,
    cancelMessage: PropTypes.func.isRequired,
    translate: PropTypes.func.isRequired,
}
