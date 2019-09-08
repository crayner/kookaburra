'use strict'

import React, { Component } from 'react'
import ContainerApp from "../Container/ContainerApp"
import {deleteFile} from "../component/deleteFile"


export default class DepartmentEditApp extends Component {
    constructor (props) {
        super(props)
        this.otherProps = {...props}

        this.functions = {
            manageLinkOrFile: this.manageLinkOrFile.bind(this),
            addElementCallable: this.addElement.bind(this),
            setParentState: this.setMyState.bind(this)
        }

        this.manageURLTypes = this.manageURLTypes.bind(this)

        this.state = {
            forms: props.forms,
        }
    }

    componentDidMount() {
        this.manageURLTypes({...this.state.forms.single})
    }

    setMyState(forms){
        this.manageURLTypes({...forms.single})
    }

    manageURLTypes(parentForm) {
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
        this.setMyState({single: parentForm})
    }

    addElement(element){
        element.children.type.value = 'Link'
        element.children.url.type = 'url'
        element.children.department.value = this.state.forms.single.children.id.value

        return element
    }


    render() {
        return (<ContainerApp {...this.otherProps} forms={this.state.forms} functions={this.functions} />)
    }
}
