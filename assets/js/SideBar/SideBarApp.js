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
    } = props

    function getState()
    {
        return {
            sidebarDocked: width >= 1024 && !minimised,
            sidebarOpen: sidebarOpen,
            screenWidth: width,
        }
    }

    const state = getState()
    let sidebarClass = 'px-6 pb-6'
    if (width < 1024)
        sidebarClass = 'absolute top-0 right-0 float-right px-6 pb-6'
    if (!minimised || state.sidebarOpen || state.sidebarDocked) {
        sidebarClass = ' lg:border-l'
    }

    let sidebarAttr = {
        id: 'sidebar',
        className: sidebarClass,
        style: {width: '250px'}
    }

    if (minimised && !state.sidebarOpen && !state.sidebarDocked)
        sidebarAttr.style = {width: 'auto'}


    return (
        <div {...sidebarAttr}>
            <SideBarControl content={content} state={state} functions={functions} />
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


