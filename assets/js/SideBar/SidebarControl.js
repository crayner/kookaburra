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

    let hidden = state.sidebarOpen || state.sidebarDocked
    let buttonAttr = {
        className: 'text-gray-600 float-right',
        id: 'sideBarButton',
    }

    let sideBarContentAttr = {
        className: 'md:column-2 lg:column-1 px-6 ',
        id: 'sideBarContent',
    }
    if (hidden) {
        buttonAttr.className = 'invisible'
    } else {
        sideBarContentAttr.className = 'invisible'
        sideBarContentAttr.style = {width: '0', height: '0'}
    }

    return (<div>
        <button {...buttonAttr} onClick={() => functions.onSetSidebarOpen(true)} style={{marginRight: '-1.5rem'}}>
            <span className={'fas fa-bars fa-fw fa-2x'}/></button>
        <SideBarContent content={content} sidebarAttr={sideBarContentAttr} />
    </div>)
}

SideBarControl.propTypes = {
    content: PropTypes.object.isRequired,
    state: PropTypes.object.isRequired,
    functions: PropTypes.object.isRequired,
}