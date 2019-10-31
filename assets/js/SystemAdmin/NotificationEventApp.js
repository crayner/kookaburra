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

export default class NotificationEventApp extends Component {
    constructor (props) {
        super(props)
        this.panels = props.panels ? props.panels : {}
        this.content = props.content ? props.content : null
        this.extras = props.extras
        this.translations = props.translations
        this.actionRoute = props.actionRoute

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
            toggleScopeType: this.toggleScopeType.bind(this),
        }

        this.state = {
            selectedPanel: props.selectedPanel,
            forms: {...props.forms},
            panelErrors: {},
            submit: false,
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

        let listeners = typeof forms.single.children.listeners.children === 'undefined' ? [] : forms.single.children.listeners.children
        let newListeners = []
        if (typeof listeners !== 'object')
            listeners = []
        Object.keys(listeners).map(key => {
            const child = {...listeners[key]}
            newListeners = this.setListenerChildByScope(listeners, key, child.children.scopeType.value)
        })
        forms.single.children.listeners.children = newListeners


        if (typeof panelErrors === 'undefined')
            panelErrors = this.state.panelErrors

        this.setState({
            forms: forms,
            panelErrors: panelErrors,
            submit: isSubmit(this.submit),
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
        this.setState({
            submit: true,
        })
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
        return (
            <section>
                {this.state.submit ? <div className={'waitOne info'}>{this.functions.translate('Let me ponder your request')}...</div> : ''}
                <PanelApp panels={this.panels} selectedPanel={this.state.selectedPanel} functions={this.functions} forms={this.state.forms} actionRoute={this.actionRoute} singleForm={this.singleForm} translations={this.translations} panelErrors={this.state.panelErrors} />
            </section>
        )
    }
}

NotificationEventApp.propTypes = {
    panels: PropTypes.object,
    forms: PropTypes.object,
    translations: PropTypes.object,
    content: PropTypes.string,
    actionRoute: PropTypes.string,
    selectedPanel: PropTypes.string,
    extras: PropTypes.object.isRequired,
}

NotificationEventApp.defaultProps = {
    functions: {},
    translations: {},
    forms: {},
}

