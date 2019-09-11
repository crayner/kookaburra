'use strict'

import React, { Component } from 'react'
import PropTypes from 'prop-types'
import ContainerApp from "../Container/ContainerApp"

export default class NotificationEventApp extends Component {
    constructor (props) {
        super(props)
        this.otherProps = {...props}
        this.extras = props.extras
        this.functions = {
            toggleScopeType: this.toggleScopeType.bind(this),
            setParentState: this.setMyState.bind(this),
            addElementCallable: this.addElement.bind(this),
        }

        this.state = {
            forms: props.forms,
        }
    }

    setMyState(forms) {
        this.setState({
            forms: {...forms}
        })
    }

    toggleScopeType(e, form)
    {
        const value = e.target.value
        let listeners = this.state.forms.single.children.listeners.children
        const name = form.id.replace('notification_event_listeners_', '').replace('_scope', '')
        let childKey = 'jjj'
        listeners.map((child,key) => {
            if (child.name === name)
                childKey = key
        })
        listeners = this.setListenerChildByScope(listeners,childKey,value)
        let forms = this.state.forms
        forms.single.children.listeners.children = listeners
        this.setMyState(forms)
    }

    addElement(element){
        element.children.scope.value = 'All'
        element.children.person.value = ''
        element.children.scopeTypeChoice.type = 'display'
        element.children.scopeTypeChoice.value = ''
        const length = Object.keys(element.children.scope.choices).length
        if (length === 1) {
            element.children.scope.type = 'display'
            element.children.scope.value = 'All'
        } else {
            element.children.scope.type = 'choice'
            element.children.scope.value = 'All'
        }
        return element
    }

    setListenerChildByScope(listeners,childKey,value)
    {
        let form = listeners[childKey]
        form.children.scope.value = value
        if (value === 'All' || value === '') {
            form.children.scopeTypeChoice.type = 'display'
            form.children.scopeTypeChoice.value =  ''
            const length = Object.keys(form.children.scope.choices).length
            if (length === 1) {
                form.children.scope.type = 'display'
                form.children.scope.value = 'All'
            } else {
                form.children.scope.type = 'choice'
            }
        }
        if (value === 'gibbonPersonIDStudent') {
            form.children.scopeTypeChoice.type = 'choice'
            form.children.scopeTypeChoice.value = ''
            form.children.scopeTypeChoice.choices = this.extras.students
        }
        if (value === 'gibbonYearGroupID') {
            form.children.scopeTypeChoice.type = 'choice'
            form.children.scopeTypeChoice.value = ''
            form.children.scopeTypeChoice.choices = this.extras.yearGroups
        }
        if (value === 'gibbonStaffID') {
            form.children.scopeTypeChoice.type = 'choice'
            form.children.scopeTypeChoice.value = ''
            form.children.scopeTypeChoice.choices = this.extras.staff
        }
        listeners[childKey] = form
        return listeners
    }

    render() {
        return (<ContainerApp {...this.otherProps} forms={this.state.forms} functions={this.functions} />)
    }
}

NotificationEventApp.propTypes = {
    extras: PropTypes.object.isRequired,
    forms: PropTypes.object.isRequired,
}
