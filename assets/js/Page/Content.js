'use strict'

import React from "react"
import PropTypes from 'prop-types'
import Sidebar from "../SideBar/SideBarApp"

export default function Content(props) {
    const {
        contentWidth,
        contentHeight,
        sidebar,
        minimised,
        functions,
        content,
        sidebarOpen,
        sidebarDocked,
    } = props

    let contentStyle = {}
    if (sidebarDocked || sidebarOpen) {
        contentStyle = {
            minHeight: contentHeight + 'px',
            width: (contentWidth - 286) + 'px',
        }
    }

    if (minimised && !(sidebarDocked || sidebarOpen)) {
        contentStyle = {
            width: (contentWidth ) + 'px',
        }

    }



    function buildContent() {
        let result = []
        if (Object.keys(sidebar).length > 0)
            result.push(<Sidebar key={'sidebar'} minimised={minimised} content={sidebar} functions={functions} width={contentWidth} sidebarOpen={sidebarOpen} />)
        result.push(<div key={'content'} id="content" className="w-full lg:flex-1 px-6 pb-6 lg:pt-0 overflow-x-scroll sm:overflow-x-auto min-h-full" style={contentStyle}>
            {content}
            </div>)
        return result
    }

    return (buildContent())
}

Content.propTypes = {
    contentWidth: PropTypes.number.isRequired,
    contentHeight: PropTypes.number.isRequired,
    minimised: PropTypes.bool.isRequired,
    content: PropTypes.array.isRequired,
    sidebar: PropTypes.object.isRequired,
    sidebarOpen: PropTypes.bool.isRequired,
    sidebarDocked: PropTypes.bool.isRequired,
}

Content.defaultProps = {
    action: {},
}

