'use strict'

import React from "react"
import PropTypes from 'prop-types'
import Sidebar from "../SideBar/SideBarApp"

export default function Content(props) {
    const {
        contentWidth,
        contentHeight,
        sidebar,
        functions,
        content,
        sidebarOpen
    } = props

    const state = contentState({
        height: contentHeight,
        width: contentWidth,
        sidebarOpen: sidebarOpen,
        content: typeof sidebar.content !== 'undefined' ? sidebar.content : {},
        docked: typeof sidebar.docked === 'boolean' ? sidebar.docked: false,
        minimised: typeof sidebar.minimised === 'boolean' ? sidebar.minimised : false,
    })
    
    function buildContent() {
        let result = []
        result.push(<Sidebar key={'sidebar'} functions={functions} {...state} />)
        result.push(<div {...state.contentAttr} key={'content'}>
            {content}
            </div>)
        return result
    }

    return (buildContent())

    function contentState(state) {
        state.contentAttr = {
            id: 'content',
            className: 'px-6 pb-6 pt-0 float-left',
        }

        let showSidebar = false
        if (state.docked && state.sidebarOpen === '') showSidebar = true
        if (!state.minimised && state.width > 975) showSidebar = true
        if (state.sidebarOpen === 'open') showSidebar = true

        if (state.minimised && state.sidebarOpen === '') showSidebar = false

        if (typeof state.content !== 'undefined') {
            state.contentAttr.style = {
                width: (state.width - 250) + 'px',
                minHeight: (24 + state.height) + 'px'
            }
        } else {
            state.contentAttr = {
                id: 'content',
                key: 'content',
                className: 'w-full px-6 pb-6 pt-0 float-left',
                style: {
                    minHeight: (24 + state.height) + 'px',
                },
            }
        }

        if (showSidebar) {
            state.contentAttr.style = {
                width: (state.width - 250) + 'px',
                minHeight: (24 + state.height) + 'px'
            }
            if (state.width < 976) {
                state.contentAttr.style = {
                    width: (state.width - 226) + 'px',
                    minHeight: (24 + state.height) + 'px'
                }
            }
        } else {
            state.contentAttr = {
                className: 'w-full px-6 pb-6 pt-0 float-left',
            }
        }

        state.sidebarOpen = showSidebar
        return state
    }
}

Content.propTypes = {
    sidebarOpen: PropTypes.string.isRequired,
    contentWidth: PropTypes.number.isRequired,
    contentHeight: PropTypes.number.isRequired,
    content: PropTypes.array.isRequired,
    sidebar: PropTypes.object.isRequired,
}

Content.defaultProps = {
    action: {},
}
