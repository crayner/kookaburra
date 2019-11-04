'use strict';

import React from "react"
import PropTypes from 'prop-types'

export default function Message(props) {
    const {
        message,
        cancelMessage,
        translate,
    } = props

    if (typeof message.message === 'string') {
        return (
            <div className={message.class}>
                {message.message}
                <button className={'button close ' + message.class} onClick={() => cancelMessage(message.id)}
                        title={translate('Close Message')} type='button'>
                    <span className={'fas fa-times-circle fa-fw ' + message.class}></span>
                </button>
            </div>
        )
    }

    if (typeof message.message === 'object') {
        return (
            <div className={message.class}>
                {message.message.message}<br/><span className={'small'}>{message.message.stack}</span>
                <button className={'button close ' + message.class} onClick={() => cancelMessage(message.id)}
                        title={translate('Close Message')} type='button'>
                    <span className={'fas fa-times-circle fa-fw ' + message.class}></span>
                </button>
            </div>
        )
    }

    console.log('message.message is a ' + typeof message.message)
    console.log(message)
    return null
}

Message.propTypes = {
    message: PropTypes.object.isRequired,
    cancelMessage: PropTypes.func.isRequired,
    translate: PropTypes.func.isRequired
}
