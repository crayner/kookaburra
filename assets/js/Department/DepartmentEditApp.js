'use strict'

import React, { Component } from 'react'
import PropTypes from 'prop-types'
import ContainerApp from "../Container/ContainerApp"


export default class DepartmentEditApp extends Component {
    constructor (props) {
        super(props)
        this.form = props.form
        this.otherProps = {...props}
        delete this.otherProps.form

        this.manageLinkOrFile = this.manageLinkOrFile.bind(this)

        this.functions = {
            manageLinkOrFile: this.manageLinkOrFile,
        }

        this.state = {
            values: []
        }
    }

    componentDidMount() {
        let form = {...this.form}
        let resources = form.children.resources
        let values = resources.children.map((child) => {
            const value = child.children.type.value === 'File' ? 'File' : 'Link'
            if (value === 'File') {
                child.children.url.type = 'file'
            } else {
                child.children.url.type = 'url'
            }
            return value
        })

        form.children.resources = {...resources}

        this.form = {...form}

        this.setState({
            values: values
        })
    }

    manageLinkOrFile(e) {
        let name = e.target.id.replace('department_edit_resources_', '')
        name = name.replace('_type', '')
        let child = this.panels.single.form.children.resources.children[name]
        this.form = this.panels.single.form
        let values = this.state.values
        values[name] = e.target.value
        this.setState({
            values: values
        })
    }

    render() {
        this.otherProps.panels.single.form = {...this.form}
        return (<ContainerApp {...this.otherProps} form={this.form} />)
    }
}

DepartmentEditApp.propTypes = {
    form: PropTypes.object.isRequired,
}
