'use strict'

import React, { Component } from 'react'
import PropTypes from 'prop-types'
import PanelApp from "../Panel/PanelApp"


export default class ContainerApp extends Component {
    constructor (props) {
        super(props)
        this.panels = props.panels ? props.panels : {}
        this.content = props.content ? props.content : null
        this.selectedPanel = props.selectedPanel

        if (Object.keys(this.panels).length === 0 && this.content !== null) {
            this.panels['default'] = {}
            this.panels.default['name'] = 'default'
            this.panels.default['disabled'] = true
            this.panels.default['content'] = this.content
        }

        console.log(this)
    }

    render() {
        return (
            <PanelApp panels={this.panels} selectedPanel={this.selectedPanel} />
        )
    }
}

ContainerApp.propTypes = {
    panels: PropTypes.object,
    content: PropTypes.string,
    selectedPanel: PropTypes.string,
}
