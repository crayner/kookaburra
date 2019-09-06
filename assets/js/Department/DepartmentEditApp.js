'use strict'

import React, { Component } from 'react'
import ContainerApp from "../Container/ContainerApp"


export default class DepartmentEditApp extends Component {
    constructor (props) {
        super(props)
        this.otherProps = {...props}

        this.functions = {
            manageLinkOrFile: this.manageLinkOrFile.bind(this),
            addElementCallable: this.addElement.bind(this),
            deleteElementCallable: this.deleteElement.bind(this),
            submitFormCallable: this.submitForm.bind(this),
        }

        this.mapTypeValues = this.mapTypeValues.bind(this)

        this.state = {
            values: {}
        }
    }

    componentDidMount() {
        this.setState({
            values: this.mapTypeValues(this.otherProps.forms.single)
        })
    }

    mapTypeValues(form){
        let resources = form.children.resources
        let values = {}
        if (typeof resources.children === 'object') {
            let children = []
            Object.keys(resources.children).map(key => {
                children.push(resources.children[key])
            })
            resources.children = children
        }
        if (typeof resources.children !== 'undefined' && resources.children.length > 0) {
            resources.children.map((child, key) => {
                const value = child.children.type.value === 'File' ? 'File' : 'Link'
                if (value === 'File') {
                    child.children.url.type = 'file'
                } else {
                    child.children.url.type = 'url'
                }
                child.children.type.value = value
                values[key] = value
            })
        }

        this.otherProps.forms.single.children.resources = {...resources}
        return values
    }

    manageLinkOrFile(e, form) {
        let name = form.id.replace('department_edit_resources_', '')
        name = name.replace('_type', '')
        let child = this.otherProps.forms.single.children.resources.children[name]
        form.value = e.target.value
        if (form.value === 'File') {
            child.children.url.type = 'file'
        } else {
            child.children.url.type = 'url'
        }
        this.otherProps.forms.single.children.resources.children[name] = child
        let values = this.state.values
        values[name] = form.value
        this.setState({
            values: values
        })
    }

    addElement(element){
        element.children.type.value = this.otherProps.forms.single.children.id.value
        element.children.department.value = 'Link'
        element.children.url.type = 'url'
        console.log(this)
        if (typeof this.otherProps.forms.single.children.resources.children === 'undefined') {
            this.otherProps.forms.single.children.resources.children = []
        }
        this.otherProps.forms.single.children.resources.children[element.name] = element
        let values = this.state.values
        values[element.name] = element.children.type.value
        const uuidv4 = require('uuid/v4')
        this.otherProps.forms.single.children.resources.collection_key = uuidv4()
        this.setState({
            values: values
        })
        return element
    }

    deleteElement(data, element) {
        if (typeof data.form !== 'object' || data.status === 'error') {
            // restore the deleted element to the display on error
            this.otherProps.forms.single.children.resources.children[element.name] = element
            this.setState({
                values: this.mapTypeValues(this.otherProps.forms.single)
            })
            return this.otherProps.forms.single
        }
        this.setState({
            values: this.mapTypeValues(data.form)
        })
        this.otherProps.forms.single = {...data.form}
        return data.form
    }

    submitForm(form) {
        this.setState({
            values: this.mapTypeValues(form)
        })
        return form
    }

    render() {
        return (<ContainerApp {...this.otherProps} functions={this.functions} />)
    }
}
