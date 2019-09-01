'use strict'

import React, { Component } from 'react'
import PropTypes from 'prop-types'
import Parser from "html-react-parser"
import Panels from "./Panels"
import FormApp from "../Form/FormApp"

export default class PanelApp extends Component {
    constructor (props) {
        super(props)
        this.panels = props.panels
        this.forms = props.forms
        this.selectedPanel = props.selectedPanel

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
            const name = Object.keys(this.panels)[0]
            const panel = this.panels[name]
            if (panel.content !== null) {
                return (
                    Parser(panel.content)
                )
            }
            return <FormApp {...this.props} form={this.forms[name]} />
        }
        return (
            <Panels {...this.props} panels={this.panels} selectedIndex={this.state.tabIndex} onSelectTab={this.onSelectTab} />
        )
    }
}

PanelApp.propTypes = {
    panels: PropTypes.object.isRequired,
    forms: PropTypes.object.isRequired,
    selectedPanel: PropTypes.string,
}
