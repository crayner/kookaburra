'use strict';

import React from 'react'
import { render } from 'react-dom'
import ContainerApp from "./Container/ContainerApp"

if (window.CONTAINER_PROPS !== 'undefined') {
    const containers = window.CONTAINER_PROPS

    for (const key in containers)
    {
        var container = containers[key]

        var target = document.getElementById(container.target)

        if (target !== null) {
            render(
                <ContainerApp
                    content={container.content}
                    panels={container.panels}
                    selectedPanel={container.selectedPanel}
                />,
                target
            )
        }
    }
}