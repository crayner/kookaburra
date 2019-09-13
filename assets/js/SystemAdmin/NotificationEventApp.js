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

    componentDidMount() {
        this.setMyState({...this.state.forms})
    }

    setMyState(forms) {
        let listeners = forms.single.children.listeners.children
        let newListeners = []
        if (typeof listeners !== 'object')
            listeners = []
        Object.keys(listeners).map(key => {
            const child = {...listeners[key]}
            newListeners = this.setListenerChildByScope(listeners, key, child.children.scopeType.value)
        })
        forms.single.children.listeners.children = newListeners
        this.setState({
            forms: {...forms}
        })
    }

    toggleScopeType(e, form)
    {
        const value = e.target.value
        let listeners = this.state.forms.single.children.listeners.children
        const name = form.id.replace('notification_event_listeners_', '').replace('_scopeType', '')
        let childKey = 'jjj'
        Object.keys(listeners).map(key => {
            let child = listeners[key]
            if (child.name === name)
                childKey = key
        })
        listeners = this.setListenerChildByScope(listeners,childKey,value)
        let forms = this.state.forms
        forms.single.children.listeners.children = listeners
        this.setMyState(forms)
    }

    addElement(element){
        element.children.scopeType.value = 'All'
        element.children.person.value = ''
        element.children.scopeID.type = 'display'
        element.children.scopeID.value = ''
        element.children.event.value = this.state.forms.single.children.id.value
        const length = Object.keys(element.children.scopeType.choices).length
        if (length === 1) {
            element.children.scopeType.type = 'display'
            element.children.scopeType.value = 'All'
        } else {
            element.children.scopeType.type = 'choice'
            element.children.scopeType.value = 'All'
        }
        return element
    }

    setListenerChildByScope(listeners,childKey,value)
    {
        let form = listeners[childKey]
        form.children.scopeType.value = value
        if (value === 'All' || value === '') {
            form.children.scopeID.type = 'display'
            form.children.scopeID.value =  ''
            const length = Object.keys(form.children.scopeType.choices).length
            if (length === 1) {
                form.children.scopeType.type = 'display'
                form.children.scopeType.value = 'All'
            } else {
                form.children.scopeType.type = 'choice'
            }
        }
        form.children.scopeID.placeholder = form.children.person.placeholder
        if (value === 'gibbonPersonIDStudent') {
            form.children.scopeID.type = 'choice'
            form.children.scopeID.choices = this.extras.students
        }
        if (value === 'gibbonYearGroupID') {
            form.children.scopeID.type = 'choice'
            form.children.scopeID.choices = this.extras.yearGroups
        }
        if (value === 'gibbonStaffID') {
            form.children.scopeID.type = 'choice'
            form.children.scopeID.choices = this.extras.staff
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
