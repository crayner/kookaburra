'use strict'

import React, { Component } from 'react'
import PropTypes from 'prop-types'
import ContainerApp from "../Container/ContainerApp"

export default class ThirdPartyApp extends Component {
    constructor (props) {
        super(props)
        this.otherProps = {...props}
        this.extras = props.extras
        this.functions = {
            toggleSMSRows: this.toggleSMSRows.bind(this),
            setParentState: this.setMyState.bind(this),
        }

        this.toggleSMSRowsOnValue = this.toggleSMSRowsOnValue.bind(this)

        this.state = {
            forms: props.forms,
        }
    }

    componentDidMount() {
        let value = this.state.forms.SMS.children.smsSettings.children['Messenger__smsGateway'].value
        if (value === null || value === '') return
        this.toggleSMSRowsOnValue(value)
    }

    setMyState(forms) {
        this.setState({
            forms: forms
        })
    }

    toggleSMSRows(e, form) {
        this.toggleSMSRowsOnValue(e.target.value)
    }

    toggleSMSRowsOnValue(value) {
        let forms = {...this.state.forms}
        let parentForm = {...forms.SMS}
        const settings = this.extras[value]
        let smsSettings = parentForm.children.smsSettings
        Object.keys(settings).map(name => {
            const values = settings[name]
            smsSettings.children[name].row_style = 'hidden'
            if (values.visible === true) {
                smsSettings.children[name].row_style = 'standard'
            }
            smsSettings.children[name].label = values.label
            smsSettings.children[name].help = null
            if (typeof values.help === 'string')
                smsSettings.children[name].help = values.help
        })
        parentForm.children.smsSettings = smsSettings
        forms.SMS = parentForm
        this.setMyState(forms)

    }

    render() {
        return (<ContainerApp {...this.otherProps} forms={this.state.forms} functions={this.functions} />)
    }
}

ThirdPartyApp.propTypes = {
    extras: PropTypes.object.isRequired,
    forms: PropTypes.object.isRequired,
}
