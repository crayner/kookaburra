'use strict'

import React from 'react'
import PropTypes from 'prop-types'
import SideBarControl from "./SidebarControl"

export default function SideBar(props) {
    const {
        content,
        minimised,
        width,
        functions,
        sidebarOpen,
        height
    } = props

    function getState()
    {
        let state = {}
        state = {
            sidebarOpen: sidebarOpen,
            screenWidth: width,
            height: height,
            content: content
        }

        state.sidebarAttr = {
            id: 'sidebar',
            style: {width: '250px'},
            className: 'absolute top-0 right-0 float-right px-6 pb-6 pt-0',
        }
        if (sidebarOpen)
            state.sidebarAttr.className += ' lg:border-l'

        state.sidebarContentAttr = {
            id: 'sideBarContent',
        }

        let hidden = state.sidebarOpen
        state.buttonAttr = {
            className: 'text-gray-600 float-right',
            id: 'sideBarButton',
            style: {
                marginRight: '-1.5rem'
            },
        }


        if (hidden) {
            state.buttonAttr.className = 'hidden'
        } else {
            state.sidebarContentAttr.className = 'invisible'
            state.sidebarAttr.style = {
                width: '35px',
            }
        }

        return state
    }

    const state = getState()

    return (
        <div {...state.sidebarAttr}>
            <SideBarControl state={state} functions={functions} />
        </div>
    )
}

SideBar.propTypes = {
    minimised: PropTypes.bool.isRequired,
    content: PropTypes.object.isRequired,
    functions: PropTypes.object.isRequired,
    width: PropTypes.number.isRequired,
    sidebarOpen: PropTypes.bool.isRequired,
}

export function sideBarState(state) {
    return state
}
