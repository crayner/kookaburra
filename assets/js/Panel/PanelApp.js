'use strict'

import React, { Component } from 'react'
import PropTypes from 'prop-types'
import Parser from "html-react-parser"
import Panels from "./Panels"

export default class PanelApp extends Component {
    constructor (props) {
        super(props)
        this.panels = props.panels
        this.selectedPanel = props.selectedPanel
        this.globalForm = props.globalForm

        this.state = {
            tabIndex: this.panels[this.selectedPanel].index,
        }

        this.onSelectTab = this.onSelectTab.bind(this)

    }

    onSelectTab(tabIndex)
    {
        this.setState({
            tabIndex: tabIndex,
        })
    }

    render() {
        if (Object.keys(this.panels).length === 1) {
            const key = Object.keys(this.panels)[0]
            const panel = this.panels[key]
            if (panel.content !== null) {
                return (
                    Parser(panel.content)
                )
            }
        }
        return (
            <Panels panels={this.panels} selectedIndex={this.state.tabIndex} onSelectTab={this.onSelectTab} globalForm={this.globalForm} />
        )
    }
}

PanelApp.propTypes = {
    panels: PropTypes.object.isRequired,
    selectedPanel: PropTypes.string,
    globalForm: PropTypes.bool.isRequired,
}
