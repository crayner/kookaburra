'use strict'

import React, { Component } from 'react'
import PropTypes from 'prop-types'
import PanelApp from "../Panel/PanelApp"
import {isEmpty} from '../component/isEmpty'
import {openPage} from "../component/openPage"
import {fetchJson} from "../component/fetchJson"
import {createPassword} from "../component/createPassword"

export default class ContainerApp extends Component {
    constructor (props) {
        super(props)
        this.panels = props.panels ? props.panels : {}
        this.content = props.content ? props.content : null
        this.functions = props.functions
        this.translations = props.translations
        this.actionRoute = props.actionRoute

        if (Object.keys(this.panels).length === 0 && this.content !== null) {
            this.panels['default'] = {}
            this.panels.default['name'] = 'default'
            this.panels.default['disabled'] = true
            this.panels.default['content'] = this.content
        }
        this.functions.translate = this.translate.bind(this)
        this.functions.openUrl = this.openUrl.bind(this)
        this.functions.downloadFile = this.downloadFile.bind(this)
        this.functions.onSelectTab = this.onSelectTab.bind(this)
        this.functions.deleteFile = this.deleteFile.bind(this)
        this.functions.submitForm = this.submitForm.bind(this)
        this.functions.onElementChange = this.onElementChange.bind(this)
        this.functions.deleteElement = this.deleteElement.bind(this)
        this.functions.addElement = this.addElement.bind(this)
        this.functions.onCKEditorChange = this.onCKEditorChange.bind(this)
        this.functions.generateNewPassword = this.generateNewPassword.bind(this)
        this.functions.deleteFile = this.deleteFile.bind(this)
        this.functions.calcFormCount = this.calcFormCount.bind(this)
        this.getParentForm = this.getParentForm.bind(this)
        this.setParentState = this.setParentState.bind(this)
        this.getParentFormName = this.getParentFormName.bind(this)
        this.mergeParentForm = this.mergeParentForm.bind(this)
        this.state = {
            selectedPanel: props.selectedPanel,
            forms: props.forms,
            submit: false
        }
        this.formNames = {}
        this.submit = {}
    }

    componentDidMount() {
        Object.keys(this.state.forms).map(name => {
            const form = this.state.forms[name]
            this.formNames[form.name] = name
            this.submit[form.name] = false
        })
    }

    setParentState(forms){
        if (typeof this.functions.setParentState === 'function') {
            this.functions.setParentState(forms)
        } else {
            this.setState({
                forms: forms,
            })
        }
    }
    translate(id){
        if (isEmpty(this.translations[id])) {
            console.error('Unable to translate: ' + id)
            return id
        }
        return this.translations[id]
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

    downloadFile(file) {
        const route = '/resource/' + btoa(file) + '/' + this.actionRoute + '/download/'
        openPage(route, {target: '_blank'}, false)
    }

    openUrl(file) {
        window.open(file, '_blank')
    }

    deleteFile(form) {
        let route = '/resource/' + btoa(form.value) + '/' + this.actionRoute + '/delete/'
        if (typeof form.delete_security !== 'undefined' && form.delete_security !== false)
            route = '/resource/' + btoa(form.value) + '/' + form.delete_security + '/delete/'
        let parentForm = this.getParentForm(form)
        fetchJson(
            route,
            {},
            false)
            .then(data => {
                if (data.status === 'success') {
                    let errors = parentForm.errors
                    errors = errors.concat(data.errors)
                    parentForm.errors = errors
                    this.setParentState(this.mergeParentForm(this.getParentFormName(form), this.changeFormValue(parentForm,form,'')))
                } else {
                    let errors = parentForm.errors
                    errors = errors.concat(data.errors)
                    parentForm.errors = errors
                    this.setParentState(this.mergeParentForm(this.getParentFormName(form), parentForm))

                }
            }).catch(error => {
                let errors = parentForm.errors
                errors.push({'class': 'error', 'message': error})
                parentForm.errors = errors
                this.setParentState(this.mergeParentForm(this.getParentFormName(form), parentForm))
            })
    }

    generateNewPassword(form) {
        const password = createPassword(form.generateButton.passwordPolicy)
        let fullForm = this.getParentForm(form)
        let id = form.id.replace('first', 'second')
        fullForm = {...this.changeFormValue(fullForm,form,password)}
        let second = this.findElementById(fullForm, id, {})
        alert(form.generateButton.alertPrompt + ': ' + password)
        fullForm = this.changeFormValue(fullForm,second,password)
        this.setParentState(this.mergeParentForm(this.getParentFormName(form),fullForm))
    }

    onCKEditorChange(event, editor, form) {
        const data = editor.getData()
        this.setParentState(this.mergeParentForm(this.getParentFormName(form), this.changeFormValue(this.getParentForm(form),form,data)))
    }

    calcFormCount(form, formCount) {
        if (typeof form.children === 'array' && form.children.length > 0) {
            this.state.form.children.map(child => {
                formCount = this.calcFormCount(child, formCount)
            })
        } else if (typeof form.children === 'object' && Object.keys(form.children).length > 0) {
            Object.keys(form.children).map(key => {
                let child = form.children[key]
                formCount = this.calcFormCount(child, formCount)
            })
        }
        formCount++
        return formCount
    }

    changeFormValue(form, find, value) {
        if (typeof form.children === 'array' && form.children.length > 0) {
            form.children.map((child,key) => {
                if (child.id === find.id) {
                    child.value = value
                }
                form.children[key] = this.changeFormValue(child, find, value)
            })
            return form
        } else if (typeof form.children === 'object' && Object.keys(form.children).length > 0) {
            Object.keys(form.children).map(key => {
                let child = form.children[key]
                if (child.id === find.id) {
                    child.value = value
                }
                form.children[key] = this.changeFormValue(child, find, value)
            })
            return form
        } else {
            return form
        }
    }

    getParentForm(form) {
        return this.state.forms[this.getParentFormName(form)]
    }

    getParentFormName(form) {
        return this.formNames[form.full_name.substring(0, form.full_name.indexOf('['))]
    }

    mergeParentForm(name, form){
        let forms = this.state.forms
        forms[name] = {...form}
        return forms
    }

    onElementChange(e, form) {
        let parentForm = this.getParentForm(form)
        const parentName = this.getParentFormName(form)
        if (form.type === 'toggle') {
            let value = form.value === 'Y' ? 'N' : 'Y'
            this.setState({
                forms: this.mergeParentForm(parentName, this.changeFormValue(parentForm,form,value)),
            })
            return
        }
        if (form.type === 'file') {
            let value = e.target.files[0]
            let readFile = new FileReader()
            readFile.readAsDataURL(value)
            readFile.onerror = (e) => {
                parentForm.errors.push({'class': 'error', 'message': this.functions.translations('A problem occurred loading the file.')})
                this.setState({
                    forms: this.mergeParentForm(parentName, this.changeFormValue(parentForm,form,value)),
                })
            }
            readFile.onload = (e) => {
                value = e.target.result
                this.setState({
                    forms: this.mergeParentForm(parentName, this.changeFormValue(parentForm,form,value)),
                })
            }
            return
        }
        let value = e.target.value
        this.setState({
            forms: this.mergeParentForm(parentName, this.changeFormValue(parentForm,form,value)),
        })
    }

    buildFormData(data, form) {
        if (typeof form.children === 'array' && form.children.length > 0) {
            form.children.map(child => {
                data[child.name] = this.buildFormData({}, child)
                //this.setMessageByElementErrors(child)
            })
            return data
        } else if (typeof form.children === 'object' && Object.keys(form.children).length > 0) {
            Object.keys(form.children).map(key => {
                let child = form.children[key]
                data[child.name] = this.buildFormData({}, child)
                //this.setMessageByElementErrors(child)
            })
            return data
        } else {
            //this.setMessageByElementErrors(form)
            return form.value
        }
    }

    submitForm(e,form) {
        const parentName = this.getParentFormName(form)
        if (this.submit[parentName]) return
        this.submit[parentName] = true
        this.setState({
            submit: true
        })
        let parentForm = this.getParentForm(form)
        let data = this.buildFormData({}, parentForm)
        fetchJson(
            parentForm.action,
            {method: parentForm.method, body: JSON.stringify(data)},
            false)
            .then(data => {
                let errors = parentForm.errors
                errors = errors.concat(data.errors)
                let form = typeof this.functions.submitFormCallable === 'function' ? this.functions.submitFormCallable(data.form) : data.form
                parentForm.errors = errors
                this.submit[parentName] = false
                this.setState({
                    submit: false,
                })
                this.setParentState(this.mergeParentForm(parentName, form))
            }).catch(error => {
                parentForm.errors.push({'class': 'error', 'message': error})
                this.submit[parentName] = false
                this.setState({
                    submit: false,
                })
                this.setParentState(this.mergeParentForm(parentName, form))
        })
    }

    deleteFormElement(form,element){
        if (typeof form.children === 'object') {
            Object.keys(form.children).map(key => {
                let child = this.deleteFormElement(form.children[key],element)
                if (child.id === element.id) {
                    delete form.children[key]
                }
            })
        }
        if (typeof form.children === 'array') {
            form.children.map((child,key) => {
                child = this.deleteFormElement(child,element)
                if (child.id === element.id) {
                    form.children.splice(key, 1)
                }
            })
        }
        return form
    }

    findElementById(form, id, element) {
        if (typeof element.id === 'string' && element.id === id)
            return element
        if (typeof form.children === 'object') {
            Object.keys(form.children).map(key => {
                let child = form.children[key]
                if (child.id === id)
                    element = child
                element = this.findElementById(form.children[key],id,element)
            })
            return element
        }
        if (typeof form.children === 'array') {
            form.children.map((child, key) => {
                if (child.id === id)
                    element = child
                element = this.findElementById(child,id,element)
            })
            return element
        }
        return element
    }

    deleteElement(element) {
        let parentForm = this.getParentForm(element)
        const restoreForm = parentForm
        parentForm = this.deleteFormElement(parentForm, element)
        this.setParentState(this.mergeParentForm(this.getParentFormName(element),parentForm))
        if (typeof element.children.id === 'object') {
            let id = element.id.replace('_' + element.name,'')
            let collection = this.findElementById(parentForm, id, {})
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
                        if (typeof this.functions.deleteElementCallable === 'function') element = this.functions.deleteElementCallable(data, element)
                        this.setParentState(this.mergeParentForm(this.getParentFormName(element), parentForm))
                    } else {
                        this.setParentState(this.mergeParentForm(this.getParentFormName(element),{...restoreForm}))
                    }
                }).catch(error => {
                    parentForm = {...restoreForm}
                    let errors = parentForm.errors
                    errors.push({'class': 'error', 'message': error})
                    parentForm.errors = errors
                    this.setParentState(this.mergeParentForm(this.getParentFormName(form), parentForm))
                })
        }
    }

    replaceName(element, id) {
        if (typeof element.children === 'object') {
            Object.keys(element.children).map(childKey => {
                let child = this.replaceName({...element.children[childKey]}, id)
                element.children[childKey] = child
            })
        }
        element.name = element.name.replace('__name__', id)
        element.id = element.id.replace('__name__', id)
        element.full_name = element.full_name.replace('__name__', id)
        if (typeof element.label === 'string')
            element.label = element.label.replace('__name__', id)
        return element
    }

    replaceFormElement(form, element) {
        if (typeof form.children === 'object') {
            Object.keys(form.children).map(key => {
                let child = this.replaceFormElement(form.children[key],element)
                if (child.id === element.id)
                    form.children[key] = element
            })
        }
        if (typeof form.children === 'array') {
            form.children.map((child,key) => {
                child = this.replaceFormElement(child,element)
                if (child.id === element.id)
                    form.children[key] = element
            })
        }
        if (form.id === element.id)
            form = element
        return form
    }

    addElement(form) {
        const uuidv4 = require('uuid/v4')
        let id = uuidv4()
        let element = this.replaceName({...form.prototype}, id)
        let parentForm = this.getParentForm(form)
        let parentFormName = this.getParentFormName(form)
        delete element.children.id
        if (typeof form.children === 'undefined')
            form.children = []
        if (typeof this.functions.addElementCallable === 'function') {
            element = this.functions.addElementCallable(element)
        }
        form.children.push(element)
        parentForm = this.replaceFormElement(parentForm, form)

        this.setState({
            forms: this.mergeParentForm(parentFormName,parentForm)
        })
    }

    render() {
        return (
            <section>
                {this.state.submit ? <div className={'info'}>{this.functions.translate('Let me ponder your request')}...</div> : ''}
                <PanelApp panels={this.panels} selectedPanel={this.state.selectedPanel} functions={this.functions} forms={this.state.forms} actionRoute={this.actionRoute} />
            </section>
        )
    }
}

ContainerApp.propTypes = {
    panels: PropTypes.object,
    forms: PropTypes.object,
    functions: PropTypes.object,
    translations: PropTypes.object,
    content: PropTypes.string,
    actionRoute: PropTypes.string,
    selectedPanel: PropTypes.string,
}

ContainerApp.defaultProps = {
    functions: {},
    translations: {},
    forms: {},
}
