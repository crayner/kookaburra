'use strict';

import React from "react"
import PropTypes from 'prop-types'

export default function Message(props) {
    const {
        message,
        cancelMessage,
    } = props

    return (
        <div className={message.class}>
            {message.message}
            <button className={'button close ' + message.class} onClick={()=> cancelMessage(message.id)} title={'Close Message'} type='button'>
                <span className={'fas fa-times-circle fa-fw ' + message.class}></span>
            </button>
        </div>
    )
}

Message.propTypes = {
    message: PropTypes.object.isRequired,
    cancelMessage: PropTypes.func.isRequired,
}
