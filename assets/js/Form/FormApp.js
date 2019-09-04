'use strict'

import React, { Component } from 'react'
import PropTypes from 'prop-types'
import Row from "./Template/Table/Row"
import {fetchJson} from "../component/fetchJson"
import Messages from "../component/Messages"
import {createPassword} from "../component/createPassword"

export default class FormApp extends Component {
    constructor (props) {
        super(props)
        this.columnCount = props.form.column_count
        this.functions = props.functions
        this.columns = props.form.columns

        this.functions.submitForm = this.submitForm.bind(this)
        this.functions.onElementChange = this.onElementChange.bind(this)
        this.functions.deleteElement = this.deleteElement.bind(this)
        this.functions.addElement = this.addElement.bind(this)
        this.functions.onCKEditorChange = this.onCKEditorChange.bind(this)
        this.functions.generateNewPassword = this.generateNewPassword.bind(this)
        this.replaceName = this.replaceName.bind(this)
        this.buildFormData = this.buildFormData.bind(this)
        this.replaceFormElement = this.replaceFormElement.bind(this)
        this.deleteFormElement = this.deleteFormElement.bind(this)
        this.findElementById = this.findElementById.bind(this)
        this.functions.calcFormCount = this.calcFormCount.bind(this)
        this.calcFormCount = this.calcFormCount.bind(this)
        this.state = {
            errors: [],
            form: props.form,
            formCount: 0,
        }
        this.submit = false
    }

    componentDidMount() {
        this.setState({
            formCount: this.calcFormCount({...this.state.form}, 0)
        })
    }

    generateNewPassword(form) {
        const password = createPassword(form.generateButton.passwordPolicy)
        let id = form.id.replace('first', 'second')
        let fullForm = {...this.state.form}
        fullForm = {...this.changeFormValue(fullForm,form,password)}
        let second = this.findElementById(fullForm, id, {})
        alert(form.generateButton.alertPrompt + ': ' + password)
        fullForm = {...this.changeFormValue(fullForm,second,password)}
        this.setState({
            errors: this.state.errors,
            form: fullForm,
        })
    }

    onCKEditorChange(event, editor, form) {
        const data = editor.getData()
        this.setState({
            errors: this.state.errors,
            form: {...this.changeFormValue(this.state.form,form,data)},
        })
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

    onElementChange(e, form) {
        if (form.type === 'toggle') {
            let value = form.value === 'Y' ? 'N' : 'Y'
            this.setState({
                errors: this.state.errors,
                form: {...this.changeFormValue(this.state.form,form,value)},
            })
            return
        }
        if (form.type === 'file') {
            let value = e.target.files[0]
            let readFile = new FileReader()
            readFile.readAsDataURL(value)
            readFile.onerror = (e) => {
                let errors = this.state.errors
                errors.push({'class': 'error', 'message': this.functions.translations('A problem occurred loading the file.')})
                this.setState({
                    errors: errors,
                })
            }
            readFile.onload = (e) => {
                value = e.target.result
                this.setState({
                    errors: this.state.errors,
                    form: {...this.changeFormValue(this.state.form,form,value)},
                })
            }
            return
        }
        this.setState({
            errors: this.state.errors,
            form: {...this.changeFormValue(this.state.form,form,e.target.value)},
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

    submitForm() {
        if (this.submit) return
        this.submit = true
        let data = this.buildFormData({}, this.state.form)
        console.log(data)
        fetchJson(
            this.state.form.action,
            {method: this.state.form.method, body: JSON.stringify(data)},
            false)
            .then(data => {
                let errors = this.state.errors
                errors = errors.concat(data.errors)
                let form = typeof this.functions.submitFormCallable === 'function' ? this.functions.submitFormCallable(data.form) : data.form
                this.submit = false
                this.setState({
                    errors: errors,
                    form: form,
                })
            }).catch(error => {
                let errors = this.state.errors
                errors.push({'class': 'error', 'message': error})
                this.submit = false
                this.setState({
                    errors: errors,
                })
        })
    }

    deleteFormElement(form,element){
        if (typeof form.children === 'object') {
            Object.keys(form.children).map(key => {
                let child = this.deleteFormElement(form.children[key],element)
                if (child.id === element.id)
                    delete form.children[key]
            })
        }
        if (typeof form.children === 'array') {
            form.children.map((child,key) => {
                child = this.deleteFormElement(child,element)
                if (child.id === element.id)
                    form.children.splice(key,1)
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
        let form = this.deleteFormElement({...this.state.form}, element)
        this.setState({
            form: form,
            formCount: this.calcFormCount({...form}, 0)
        })
        if (typeof element.children.id === 'object') {
            let id = element.id.replace('_' + element.name,'')
            let collection = this.findElementById({...this.state.form}, id, {})
            let route = collection.element_delete_route
            if (typeof collection.element_delete_options !== 'object') collection.element_delete_options = {}
            Object.keys(collection.element_delete_options).map(search => {
                let replace = collection.element_delete_options[search]
                route = route.replace(search, element.children[replace].value)
            })
            fetchJson(route, [], false)
                .then((data) => {
                    let newForm = this.state.form
                    if (typeof this.functions.deleteElementCallable === 'function') newForm = this.functions.deleteElementCallable(data, element)
                    this.setState({
                        errors: data.errors,
                        form: newForm,
                        formCount: this.calcFormCount(newForm, 0)
                    })
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
        delete element.children.id
        if (typeof this.functions.addElementCallable === 'function') {
            element = this.functions.addElementCallable(element)
        } else {
            form.children.id = element
        }
        this.setState({
            form: this.replaceFormElement({...this.state.form}, {...form})
        })
    }

    render() {
        if (this.state.form.template === 'table'){
            const rows = Object.keys(this.state.form.children).map(key => {
                const form = this.state.form.children[key]
                return (<Row key={key} form={form} functions={this.functions} columns={this.columns}/>)
            })

            let columns = []
            for (let i = 0; i < this.columns; i++) {
                columns.push(<td key={i}/>)
            }
            let dummyRow = (<tr style={{display: 'none'}}>{columns}</tr>)

            let table_attr = {}
            table_attr.className = 'smallIntBorder fullWidth standardForm relative'
            if (this.state.form.row_class !== null) table_attr.className = this.state.form.row_class

            return (<form
                        action={this.state.form.action}
                        id={this.state.form.id}
                        {...this.state.form.attr}
                        method={this.state.form.method !== undefined ? this.state.form.method : 'POST'}>
                        <Messages messages={this.state.errors} />
                        <table {...table_attr}>
                            <tbody>
                                {dummyRow}
                                {rows}
                            </tbody>
                        </table>
                    </form>
            )
        }
        // Future Expansion for grid not table
        return ''
    }
}

FormApp.propTypes = {
    form: PropTypes.object.isRequired,
    functions: PropTypes.object.isRequired,
}
