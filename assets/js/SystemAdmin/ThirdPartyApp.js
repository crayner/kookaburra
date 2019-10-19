'use strict'

import React, { Component } from 'react'
import PropTypes from 'prop-types'
import PanelApp from "../Panel/PanelApp"
import {fetchJson} from "../component/fetchJson"
import {createPassword} from "../component/createPassword"
import {
    setPanelErrors,
    trans,
    downloadFile,
    openUrl,
    buildState,
    mergeParentForm,
    getParentFormName,
    getParentForm,
    deleteFormElement,
    changeFormValue,
    replaceName,
    replaceFormElement,
    findElementById,
    buildFormData,
    isSubmit
} from "../Container/ContainerFunctions"

export default class ThirdPartyApp extends Component {
    constructor (props) {
        super(props)
        this.panels = props.panels ? props.panels : {}
        this.content = props.content ? props.content : null
        this.translations = props.translations
        this.actionRoute = props.actionRoute
        this.extras = props.extras

        if (Object.keys(this.panels).length === 0 && this.content !== null) {
            this.panels['default'] = {}
            this.panels.default['name'] = 'default'
            this.panels.default['disabled'] = true
            this.panels.default['content'] = this.content
        }

        this.functions = {
            translate: this.translate.bind(this),
            openUrl: openUrl.bind(this),
            downloadFile: downloadFile.bind(this),
            onSelectTab: this.onSelectTab.bind(this),
            deleteFile: this.deleteFile.bind(this),
            submitForm: this.submitForm.bind(this),
            onElementChange: this.onElementChange.bind(this),
            deleteElement: this.deleteElement.bind(this),
            addElement: this.addElement.bind(this),
            onCKEditorChange: this.onCKEditorChange.bind(this),
            generateNewPassword: this.generateNewPassword.bind(this),
            toggleSMSRows: this.toggleSMSRows.bind(this),
            toggleMailerRows: this.toggleMailerRows.bind(this),
            setParentState: this.setMyState.bind(this),
        }

        this.toggleSMSRowsOnValue = this.toggleSMSRowsOnValue.bind(this)
        this.toggleMailerRowsOnValue = this.toggleMailerRowsOnValue.bind(this)

        this.state = {
            selectedPanel: props.selectedPanel,
            forms: {...props.forms},
            panelErrors: {}
        }

        this.formNames = {}
        this.submit = {}
        this.singleForm = (Object.keys(props.forms).length === 1)
    }

    componentDidMount() {
        Object.keys(this.state.forms).map(name => {
            const form = this.state.forms[name]
            this.formNames[form.name] = name
            this.submit[form.name] = false
        })
        let panelErrors = {}
        if (this.singleForm) {
            panelErrors = setPanelErrors({}, {})
        }
        this.setMyState(this.state.forms, panelErrors)
    }

    setMyState(forms, panelErrors){
        if (typeof forms.panelErrors !== 'undefined') {
            panelErrors = forms.panelErrors
            forms = {...forms.forms}
        }

        if (typeof panelErrors === 'undefined')
            panelErrors = this.state.panelErrors

        let value = forms.SMS.children.smsSettings.children['Messenger__smsGateway'].value
        forms = this.toggleSMSRowsOnValue(value, {...forms})
        value = forms['E-Mail'].children.emailSettings.children['System__enableMailerSMTP'].value
        forms = this.toggleMailerRowsOnValue(value, forms)

        this.setState({
            forms: forms,
            panelErrors: panelErrors,
        })
    }

    toggleSMSRows(e, form) {
        this.setMyState(this.toggleSMSRowsOnValue(e.target.value, {...this.state.forms}))
    }

    toggleMailerRows(e, form) {
        this.setMyState(this.toggleMailerRowsOnValue(e.target.value, {...this.state.forms}))
    }

    toggleSMSRowsOnValue(value, forms) {
        if (value === '' || value === null || value === 'N')
            value = 'No'
        let parentForm = {...forms.SMS}
        const settings = this.extras[value]
        let smsSettings = parentForm.children.smsSettings
        smsSettings.children['Messenger__smsGateway'].value = value
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
        if (value === '' || value === null || value === 'N')
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

    translate(id){
        return trans(this.translations, id)
    }

    onSelectTab(tabIndex)
    {
        let selectedPanel = this.state.selectedPanel
        let i = 0
        Object.keys(this.panels).map(key => {
            if (i === tabIndex)
                selectedPanel = key
            i++
        })
        this.setState({
            selectedPanel: selectedPanel
        })
    }

    deleteFile(form) {
        let route = '/resource/' + btoa(form.value) + '/' + this.actionRoute + '/delete/'
        if (typeof form.delete_security !== 'undefined' && form.delete_security !== false)
            route = '/resource/' + btoa(form.value) + '/' + form.delete_security + '/delete/'
        let parentForm = getParentForm(this.state.forms,form)
        fetchJson(
            route,
            {},
            false)
            .then(data => {
                if (data.status === 'success') {
                    let errors = parentForm.errors
                    errors = errors.concat(data.errors)
                    parentForm.errors = errors
                    this.setMyState(
                        buildState(mergeParentForm(this.state.forms,getParentFormName(this.formNames,form), changeFormValue(parentForm,form,'')), this.singleForm)
                    )
                } else {
                    let errors = parentForm.errors
                    errors = errors.concat(data.errors)
                    parentForm.errors = errors
                    this.setMyState(
                        buildState(mergeParentForm(this.state.forms,getParentFormName(this.formNames,form), parentForm), this.singleForm)
                    )
                }
            }).catch(error => {
            let errors = parentForm.errors
            errors.push({'class': 'error', 'message': error})
            parentForm.errors = errors
            this.setMyState(
                buildState(mergeParentForm(this.state.forms,getParentFormName(this.formNames,form), parentForm), this.singleForm)
            )
        })
    }

    generateNewPassword(form) {
        const password = createPassword(form.generateButton.passwordPolicy)
        let fullForm = getParentForm(this.state.forms,form)
        let id = form.id.replace('first', 'second')
        fullForm = {...changeFormValue(fullForm,form,password)}
        let second = findElementById(fullForm, id, {})
        alert(form.generateButton.alertPrompt + ': ' + password)
        fullForm = changeFormValue(fullForm,second,password)
        this.setMyState(buildState(mergeParentForm(this.state.forms,getParentFormName(this.formNames,form),fullForm)))
    }

    onCKEditorChange(event, editor, form) {
        const data = editor.getData()
        this.setMyState(buildState(mergeParentForm(this.state.forms,getParentFormName(this.formNames,form), changeFormValue(getParentForm(this.state.forms,form),form,data))))
    }

    onElementChange(e, form) {
        const submitOnChange = form.submit_on_change
        let parentForm = getParentForm(this.state.forms,form)
        const parentName = getParentFormName(this.formNames,form)
        if (form.type === 'toggle') {
            let value = form.value === 'Y' ? 'N' : 'Y'
            this.setMyState(buildState(mergeParentForm(this.state.forms,parentName, changeFormValue(parentForm,form,value)), this.singleForm))
            return
        }
        if (form.type === 'file') {
            let value = e.target.files[0]
            let readFile = new FileReader()
            readFile.readAsDataURL(value)
            readFile.onerror = (e) => {
                parentForm.errors.push({'class': 'error', 'message': this.functions.translations('A problem occurred loading the file.')})
                this.setMyState(buildState(mergeParentForm(this.state.forms,parentName, changeFormValue(parentForm,form,value)), this.singleForm))
            }
            readFile.onload = (e) => {
                value = e.target.result
                this.setMyState(buildState(mergeParentForm(this.state.forms,parentName, changeFormValue(parentForm,form,value))))
            }
            return
        }
        let value = e.target.value
        form.value = value
        const newValue = changeFormValue({...parentForm},form,value)
        this.setMyState(buildState(mergeParentForm(this.state.forms,parentName, newValue), this.singleForm))
        if (submitOnChange)
            this.submitForm({},form)
    }

    submitForm(e,form) {
        const parentName = getParentFormName(this.formNames,form)
        if (this.submit[parentName]) return
        this.submit[parentName] = true
        this.setMyState(buildState({...this.state.forms}, this.singleForm))
        let parentForm = {...getParentForm(this.state.forms,form)}
        let data = buildFormData({}, parentForm)
        fetchJson(
            parentForm.action,
            {method: parentForm.method, body: JSON.stringify(data)},
            false)
            .then(data => {
                if (data.status === 'redirect') {
                    window.open(data.redirect,'_self');
                } else {
                    let errors = parentForm.errors
                    errors = errors.concat(data.errors)
                    let form = {...data.form}
                    form.errors = errors
                    this.submit[parentName] = false
                    this.setMyState(buildState({...mergeParentForm(this.state.forms,parentName, {...form})}, this.singleForm), setPanelErrors({...form}, {}))
                }
            }).catch(error => {
            parentForm.errors.push({'class': 'error', 'message': error})
            this.submit[parentName] = false
            this.setMyState(buildState({...mergeParentForm(this.state.forms,parentName, {...parentForm})}, this.singleForm), setPanelErrors({...form}, {}))
        })
    }

    deleteElement(element) {
        let parentForm = getParentForm(this.state.forms,element)
        const restoreForm = parentForm
        parentForm = deleteFormElement(parentForm, element)
        this.setMyState(
            buildState(mergeParentForm(this.state.forms,getParentFormName(this.formNames,element),parentForm), this.singleForm)
        )
        if (typeof element.never_saved !== 'boolean') {
            let id = element.id.replace('_' + element.name,'')
            let collection = findElementById(parentForm, id, {})
            let route = collection.element_delete_route
            if (typeof collection.element_delete_options !== 'object') collection.element_delete_options = {}
            let fetch = true
            Object.keys(collection.element_delete_options).map(search => {
                let replace = collection.element_delete_options[search]
                route = route.replace(search, element.children[replace].value)
                if (parseInt(element.children[replace].value) < 1) {
                    fetch = false
                }
            })
            if (fetch === false) return

            fetchJson(route, [], false)
                .then((data) => {
                    let errors = parentForm.errors
                    errors = errors.concat(data.errors)
                    parentForm.errors = errors
                    if (data.status === 'success') {
                        this.setMyState(
                            buildState(mergeParentForm(this.state.forms,getParentFormName(this.formNames,element), parentForm), this.singleForm)
                        )
                    } else {
                        this.setMyState(
                            buildState(mergeParentForm(this.state.forms,getParentFormName(this.formNames,element), {...restoreForm}), this.singleForm)
                        )
                    }
                }).catch(error => {
                parentForm = {...restoreForm}
                let errors = parentForm.errors
                errors.push({'class': 'error', 'message': error})
                parentForm.errors = errors
                this.setMyState(
                    buildState(mergeParentForm(this.state.forms,getParentFormName(this.formNames,element), parentForm), this.singleForm)
                )
            })
        }
    }

    addElement(form) {
        const uuidv4 = require('uuid/v4')
        let id = uuidv4()
        let element = {...replaceName({...form.prototype}, id)}
        let parentForm = {...getParentForm(this.state.forms,form)}
        let parentFormName = getParentFormName(this.formNames,form)
        element.children.id.value = id
        if (typeof form.children === 'object'){
            let newChildren = []
            Object.keys(form.children).map(key => {
                newChildren.push({...form.children[key]})
            })
            form.children = newChildren
        }
        if (typeof form.children === 'undefined')
            form.children = []

        element.never_saved = true

        form.children.push({...element})

        parentForm = {...replaceFormElement(parentForm, form)}

        this.setMyState(buildState({...mergeParentForm(this.state.forms,parentFormName,parentForm)}, this.singleForm))
    }

    render() {
        return (
            <section>
                {isSubmit(this.submit)  ? <div className={'waitOne info'}>{this.functions.translate('Let me ponder your request')}...</div> : ''}
                <PanelApp panels={this.panels} selectedPanel={this.state.selectedPanel} functions={this.functions} forms={this.state.forms} actionRoute={this.actionRoute} singleForm={this.singleForm} translations={this.translations} panelErrors={this.state.panelErrors} />
            </section>
        )
    }
}

ThirdPartyApp.propTypes = {
    panels: PropTypes.object,
    forms: PropTypes.object,
    translations: PropTypes.object,
    content: PropTypes.string,
    actionRoute: PropTypes.string,
    selectedPanel: PropTypes.string,
}

ThirdPartyApp.defaultProps = {
    functions: {},
    translations: {},
    forms: {},
}

