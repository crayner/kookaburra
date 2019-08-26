'use strict';

import React from 'react'
import { render } from 'react-dom'
import ContainerApp from "./Container/ContainerApp"
import Components from './component/Components'

if (window.CONTAINER_PROPS !== 'undefined') {
    const containers = window.CONTAINER_PROPS

    for (const key in containers)
    {
        var container = containers[key]

        var target = document.getElementById(container.target)
        if (container.application !== null && Components[container.application] !== undefined && target !== null) {
            const ComponentApplication = Components[container.application];
            render(<ComponentApplication
                content={container.content}
                panels={container.panels}
                selectedPanel={container.selectedPanel}
                globalForm={container.globalForm}
                form={container.form}
            />, target)
        } else if (target !== null) {
            render(
                <ContainerApp
                    content={container.content}
                    panels={container.panels}
                    selectedPanel={container.selectedPanel}
                    globalForm={container.globalForm}
                    form={container.form}
                />,
                target
            )
        }
    }
}