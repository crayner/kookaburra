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

        this.state = {
            forms: props.forms,
        }
    }

    componentDidMount() {
        let parentForm = {...this.state.forms.single}
        parentForm.children.resources.children.map((child,key) => {
            if (child.children.type.value === 'File') {
                parentForm.children.resources.children[key].children.url.type = 'file'
            } else {
                parentForm.children.resources.children[key].children.url.type = 'url'
                parentForm.children.resources.children[key].children.type.value = 'Link'
            }
        })
        this.setState({
            forms: {single: parentForm}
        })
    }

    manageLinkOrFile(e, form) {
        let name = form.id.replace('department_edit_resources_', '')
        name = name.replace('_type', '')
        let child = {}
        let childKey = null
        let parentForm = {...this.state.forms.single}
        parentForm.children.resources.children.map((x,key) => {
            if (name === x.name) {
                child = x
                childKey = key
            }
        })
        let value = e.target.value
        if (value === 'File') {
            child.children.url.type = 'file'
            child.children.type.value = 'File'
        } else {
            child.children.url.type = 'url'
            child.children.type.value = 'Link'
        }
        parentForm.children.resources.children[childKey] = child
        this.setState({
            forms: {single: parentForm}
        })
    }

    addElement(element){
        element.children.type.value = 'Link'
        element.children.url.type = 'url'
        let parentForm = this.otherProps.forms.single
        if (typeof parentForm.children.resources.children === 'undefined') {
            parentForm.children.resources.children = []
        }
        parentForm.children.resources.children.push(element)
        const uuidv4 = require('uuid/v4')
        parentForm.children.resources.collection_key = uuidv4()
        this.setState({
            forms: {single: parentForm}
        })
        return parentForm
    }

    deleteElement(data, element) {
        if (typeof data.form !== 'object' || data.status === 'error') {
            // restore the deleted element to the display on error
            let parentForm = {...this.otherProps.forms.single}
            parentForm.children.resources.children[element.name] = element
            this.setState({
                forms: {single: parentForm}
            })
            return parentForm
        }
        this.setState({
            forms: {...data.form}
        })
        return data.form
    }

    submitForm(form) {
        this.setState({
            forms: {single: form}
        })
        return form
    }

    render() {
        return (<ContainerApp {...this.otherProps} forms={this.state.forms} functions={this.functions} />)
    }
}
