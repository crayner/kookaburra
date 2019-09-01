'use strict'

import React, { Component } from 'react'
import PropTypes from 'prop-types'
import Message from "./Message"

export default class Messages extends Component {
    constructor(props) {
        super(props)

        this.cancelMessage = this.cancelMessage.bind(this)
    }

    cancelMessage(id) {
        let messages = this.props.messages
        messages.splice(id,1)
        this.setState({
            messages: messages
        })
    }

    render() {
        let cells = this.props.messages.map((message,key) => {
            message['id'] = key
            return <Message
                message={message}
                key={'message_' + message.id}
                cancelMessage={this.cancelMessage}
            />
        })
        if (cells.length === 0)
            return null

        return (<div>{cells}</div>)
    }
}

Messages.propTypes = {
    messages: PropTypes.array.isRequired,
}

