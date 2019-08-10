'use strict';

import React from "react"
import PropTypes from 'prop-types'

export default function Notifications(props) {
    const {
        notificationCount,
        showNotifications,
        notificationTitle,
    } = props

    const y = notificationCount

    const colour = y === 0 ? 'black' : 'white'

    return (
        <div id={'notifications'}>
            <a className={y === 0 ? 'inactive inline-block relative mr-4 fa-layers fa-fw fa-3x' : 'inline-block relative mr-4 fa-layers fa-fw fa-3x'} title={notificationTitle} onClick={showNotifications} >
                {y === 0 ?
                    <span className={'far fa-sticky-note text-gray-500'}>
                    </span>
                    :

                    <span className={'fas fa-sticky-note text-gray-800'}>
                    <span className={'fa-layers-counter'} style={{
                        color: colour,
                        fontSize: '0.8rem',
                        position: 'absolute',
                        top: '22px',
                        left: '9px'
                    }}>{y}</span>
                    </span>
                }
            </a>
        </div>
    )
}

Notifications.propTypes = {
    notificationCount: PropTypes.number,
    showNotifications: PropTypes.func.isRequired,
    notificationTitle: PropTypes.string,
}

Notifications.defaultProps = {
    notificationCount: 0,
    notificationTitle: 'Notifications',
}
