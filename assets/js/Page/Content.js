'use strict'

import React from "react"
import PropTypes from 'prop-types'
import Sidebar from "../SideBar/SideBarApp"
import PaginationApp from "../Pagination/PaginationApp"
import ContainerApp from "../Container/ContainerApp"
import Messages from "../component/Messages"

export default function Content(props) {
    const {
        contentWidth,
        contentHeight,
        sidebar,
        functions,
        content,
        sidebarOpen,
        pagination,
        containers,
        breadCrumbs,
        messages
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
        let x = []
        let loop = 0
        let crumbs = Object.keys(breadCrumbs).map(name => {
            let crumb = breadCrumbs[name]

            if (loop + 1 === Object.keys(breadCrumbs).length) {
                loop++
                return (<span key={name} className="trailEnd">{crumb.name}</span>)
            } else if (loop > 4) {
                loop++
                return (<span key={name}><a href={'#'} onClick={() => functions.getContent(crumb.url)} className="text-blue-700 underline">...</a> . </span>)
            } else {
                loop++
                return (<span key={name}><a href={'#'} onClick={() => functions.getContent(crumb.url)} className="text-blue-700 underline">{crumb.name}</a> . </span>)
            }
        })
        if (crumbs.length > 0)
            x.push(
                <div id="breadCrumbs" className="sm:pt-10 lg:pt-0" key={'breadcrumbs'}>
                    <div className="absolute lg:static top-0 my-6 text-xs text-blue-700">
                        {crumbs}
                    </div>
                </div>
            )

        if (messages.length > 0) {
            x.push(<Messages messages={messages} translate={functions.translate} key={'messages'} />)
        }

        content.map(stuff => {
            x.push(stuff)
        })

        if (Object.keys(pagination).length > 0) {
            x.push(<PaginationApp {...pagination} functions={functions} key={pagination.name} />)
        }

        if (Object.keys(containers).length > 0) {
            Object.keys(containers).map(name => {
                const container = containers[name]
                x.push(<ContainerApp                     {...container}
                                                         functions={functions} key={name} />)
            })
        }

        result.push(<Sidebar key={'sidebar'} functions={functions} {...state} />)
        result.push(<div {...state.contentAttr} key={'content'}>
            {x}
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
            if (state.width < 959) {
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
    breadCrumbs: PropTypes.object.isRequired,
    sidebar: PropTypes.object.isRequired,
    pagination: PropTypes.oneOfType([
        PropTypes.object,
        PropTypes.array,
    ]).isRequired,
    containers: PropTypes.oneOfType([
        PropTypes.object,
        PropTypes.array,
    ]).isRequired,
    messages: PropTypes.oneOfType([
        PropTypes.object,
        PropTypes.array,
    ]).isRequired,
}

Content.defaultProps = {
    action: {},
    pagination: {},
    containers: {},
    content: [],
    breadCrumbs: {}
}
