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
            toggleMailerRows: this.toggleMailerRows.bind(this),
            setParentState: this.setMyState.bind(this),
        }

        this.toggleSMSRowsOnValue = this.toggleSMSRowsOnValue.bind(this)
        this.toggleMailerRowsOnValue = this.toggleMailerRowsOnValue.bind(this)

        this.state = {
            forms: props.forms,
        }
    }

    componentDidMount() {
        this.setMyState({...this.state.forms})
    }

    setMyState(forms) {
        let value = forms.SMS.children.smsSettings.children['Messenger__smsGateway'].value
        forms = this.toggleSMSRowsOnValue(value, {...forms})
        value = forms['E-Mail'].children.emailSettings.children['System__enableMailerSMTP'].value
        forms = this.toggleMailerRowsOnValue(value, forms)
        this.setState({
            forms: {...forms}
        })
    }

    toggleSMSRows(e, form) {
        this.setMyState(this.toggleSMSRowsOnValue(e.target.value, {...this.state.forms}))
    }

    toggleMailerRows(e, form) {
        this.setMyState(this.toggleMailerRowsOnValue(e.target.value, {...this.state.forms}))
    }

    toggleSMSRowsOnValue(value, forms) {
        if (value === '' || value === null)
            value = 'No'
        let parentForm = {...forms.SMS}
        const settings = this.extras[value]
        let smsSettings = parentForm.children.smsSettings
        Object.keys(settings).map(name => {
            const values = settings[name]
            smsSettings.children[name].row_style = 'hidden'
            if (values.visible === true) {
                smsSettings.children[name].row_style = 'standard'
                smsSettings.children[name].label = values.label
                smsSettings.children[name].help = null
                if (typeof values.help === 'string')
                    smsSettings.children[name].help = values.help
            }
        })
        parentForm.children.smsSettings = smsSettings
        forms.SMS = parentForm
        return forms
    }

    toggleMailerRowsOnValue(value, forms) {
        if (value === '' || value === null)
            value = 'No'
        if (value === 'Y')
            value = "SMTP"
        let parentForm = {...forms['E-Mail']}
        const settings = this.extras['mailer'][value]
        let emailSettings = parentForm.children.emailSettings
        emailSettings.children['System__enableMailerSMTP'].value = value
        Object.keys(settings).map(name => {
            const values = settings[name]
            emailSettings.children[name].row_style = 'hidden'
            if (values.visible === true) {
                emailSettings.children[name].row_style = 'standard'
                emailSettings.children[name].label = values.label
                emailSettings.children[name].help = null
                if (typeof values.help === 'string')
                    emailSettings.children[name].help = values.help
            }
        })
        parentForm.children.emailSettings = emailSettings
        forms['E-Mail'] = parentForm
        return forms
    }

    render() {
        return (<ContainerApp {...this.otherProps} forms={this.state.forms} functions={this.functions} />)
    }
}

ThirdPartyApp.propTypes = {
    extras: PropTypes.object.isRequired,
    forms: PropTypes.object.isRequired,
}
