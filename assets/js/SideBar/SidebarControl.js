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
        className: 'md:column-2 lg:column-1',
        id: 'sideBarContent',
    }

    return (<div>
        <button {...buttonAttr} onClick={() => functions.onSetSidebarOpen(true)}>
            <span className={'fas fa-bars fa-fw fa-2x'}/></button>
        <SideBarContent content={content} sidebarAttr={sideBarContentAttr} />
    </div>)
}

SideBarControl.propTypes = {
    content: PropTypes.object.isRequired,
    state: PropTypes.object.isRequired,
    functions: PropTypes.object.isRequired,
}