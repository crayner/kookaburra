'use strict'

import React, { Component } from 'react'
import PropTypes from 'prop-types'
import PanelApp from "../Panel/PanelApp"
import ContainerApp from "../Container/ContainerApp"


export default class DepartmentEditApp extends Component {
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

        this.manageLinkOrFile = this.manageLinkOrFile.bind(this)
        this.toggleRowClass = this.toggleRowClass.bind(this)

        this.functions = {
            manageLinkOrFile: this.manageLinkOrFile,
        }

        this.state = {
            values: []
        }
    }

    componentDidMount() {
        let form = this.panels.single.form.children.resources
        let values = form.children.map((child) => {
            const value = child.children.type.value === 'File' ? 'File' : 'Link'
            this.toggleRowClass(child,value)
            return value
        })

        this.panels.single.form.children.resources = form
        this.form.children.resources = form

        this.setState({
            values: values
        })
        console.log(this)
    }

    toggleRowClass(child, value) {
        child.children.type.value = value
        if (value === 'File') {
            child.children.url.block_prefixes = ["form", "file", "file_path", "_edit_resources_entry_url"]
        } else {
            child.children.url.block_prefixes = ["form", "url", "file_path", "_edit_resources_entry_url"]
        }
        return child
    }

    manageLinkOrFile(e) {
        let name = e.target.id.replace('department_edit_resources_', '')
        name = name.replace('_type', '')
        let child = this.panels.single.form.children.resources.children[name]
        this.panels.single.form.children.resources.children[name] = this.toggleRowClass(child,e.target.value)
        this.form = this.panels.single.form
        let values = this.state.values
        values[name] = e.target.value
        this.setState({
            values: values
        })
    }

    render() {
        return (<ContainerApp {...this.props} functions={this.functions} form={{...this.form}} />)
    }
}

DepartmentEditApp.propTypes = {
    panels: PropTypes.object,
    content: PropTypes.string,
    selectedPanel: PropTypes.string,
    globalForm: PropTypes.bool,
    form: PropTypes.oneOfType([
        PropTypes.bool,
        PropTypes.object,
    ]).isRequired,
}

DepartmentEditApp.defaultProps = {
    globalForm: false,
    form: false,
}
