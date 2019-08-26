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
        this.globalForm = props.globalForm
        this.form = props.form

        if (Object.keys(this.panels).length === 0 && this.content !== null) {
            this.panels['default'] = {}
            this.panels.default['name'] = 'default'
            this.panels.default['disabled'] = true
            this.panels.default['content'] = this.content
        }

        this.functions = {

        }
    }

    fileDownload() {

    }

    render() {
        if (this.globalForm) {
            return (
                <form   action={this.form.action}
                        className={this.form.row.form.class}
                        id={this.form.id}
                        encType={this.form.row.form.enctype}
                        method={this.form.method !== undefined ? this.form.method : this.form.row.form.method}
                >
                    <PanelApp panels={this.panels} selectedPanel={this.selectedPanel} globalForm={this.globalForm} functions={this.functions} />
                </form>
            )
        }
        return (
            <PanelApp panels={this.panels} selectedPanel={this.selectedPanel} globalForm={this.globalForm} functions={this.functions} />
        )
    }
}

ContainerApp.propTypes = {
    panels: PropTypes.object,
    content: PropTypes.string,
    selectedPanel: PropTypes.string,
    globalForm: PropTypes.bool,
    form: PropTypes.oneOfType([
        PropTypes.bool,
        PropTypes.object,
    ]).isRequired,
}

ContainerApp.defaultProps = {
    globalForm: false,
    form: false,
}
