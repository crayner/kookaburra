'use strict';

import React from "react"
import PropTypes from 'prop-types'

export default function MessageWall(props) {
    const {
        messengerCount,
        showMessenger,
        messengerTitle,
    } = props

    const y = messengerCount

    const colour = y === 0 ? 'black' : 'white'

    return (
        <div id="messageWall" className="relative">
            <a href={'#'} title={messengerTitle} className={y === 0 ? 'inactive inline-block relative mr-4 fa-layers fa-fw fa-3x' : 'inline-block relative mr-4 fa-layers fa-fw fa-3x'} onClick={showMessenger}>
                <span className={y === 0 ? 'fas fa-comment-alt text-gray-500': 'fas fa-comment-alt text-gray-800'}>
                     <span className={y === 0 ? 'fa-layers-counter': 'fa-layers-counter'} style={{color: colour, fontSize: '0.8rem', position: 'absolute', top: '18px', left: '6px'}}>{y}</span>
                </span>
            </a>
        </div>
    )
}

MessageWall.propTypes = {
    messengerCount: PropTypes.number,
    showMessenger: PropTypes.func.isRequired,
    messengerTitle: PropTypes.string,
}

MessageWall.defaultProps = {
    messengerCount: 0,
    messengerTitle: 'Message Wall',
}