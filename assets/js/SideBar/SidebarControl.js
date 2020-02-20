'use strict'

import React from "react"
import PropTypes from 'prop-types'
import SideBarContent from "./SideBarContent"

export default function SideBarControl(props) {
    const {
        content,
        state,
        functions,
    } = props

    let buttonAttr = {
        className: 'text-gray-600 ' + (state.sidebarOpen || state.sidebarDocked ? 'close' : 'open'),
        id: 'sideBarButton',
    }

    let sideBarContentAttr = {
        className: state.sidebarOpen || state.sidebarDocked ? 'open px-4' : 'close px-4',
        id: 'sideBarContent',
        style: {
            maxWidth: '14rem',
        }
    }

    console.log(state)
    console.log(sideBarContentAttr)
    return (<span>
        <button {...buttonAttr} onClick={() => functions.onSetSidebarOpen(true)}>
            <span className={'fas fa-bars fa-fw fa-2x'}/></button>
        <SideBarContent content={content} sidebarAttr={sideBarContentAttr} />
    </span>)
}

SideBarControl.propTypes = {
    content: PropTypes.object.isRequired,
    state: PropTypes.object.isRequired,
    functions: PropTypes.object.isRequired,
}