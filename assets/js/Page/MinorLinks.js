'use strict'

import React from "react"
import PropTypes from 'prop-types'

export default function MinorLinks(props) {
    const {
        links
    } = props

    let content = []
    if (links !== []) {}

    return (
        <div id="minorLinks" className="mx-auto max-w-6xl text-right text-white text-xs md:text-sm px-2 xl:px-0 mt-6">
            {content}
        </div>
    )
}

MinorLinks.propTypes = {
    links: PropTypes.array.isRequired,
}
