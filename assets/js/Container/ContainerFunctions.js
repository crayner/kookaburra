'use strict';

import {isEmpty} from "../component/isEmpty"
import {openPage} from "../component/openPage"
import {fetchJson} from "../component/fetchJson"

export function setPanelErrors(form, panelErrors)
{
    if (typeof form.children === 'undefined') {
        return panelErrors
    }
    Object.keys(form.children).map(key => {
        const child = form.children[key]
        setPanelErrors(child,panelErrors)
        if (Object.keys(child.errors).length > 0 && child.panel !== false) {
            if (typeof panelErrors[child.panel] === 'undefined')
                panelErrors[child.panel] = {}
            panelErrors[child.panel].problem = true
        }
    })
    return panelErrors
}

export function trans(translations,id){
    if (isEmpty(translations[id])) {
        console.error('Unable to translate: ' + id)
        return id
    }
    return translations[id]
}


export function downloadFile(form) {
    const file = form.value
    let route = '/resource/' + btoa(file) + '/' + this.actionRoute + '/download/'
    if (typeof form.delete_security !== 'undefined' && form.delete_security !== false)
        route = '/resource/' + btoa(form.value) + '/' + form.delete_security + '/download/'
    openPage(route, {target: '_blank'}, false)
}

export function openUrl(file) {
    window.open(file, '_blank')
}


export function buildState(forms,singleForm){
    let panelErrors = {}
    let state = {}
    if (singleForm) {
        panelErrors = setPanelErrors(forms[Object.keys(forms)[0]], panelErrors)
    }
    state = {
        forms: forms,
        panelErrors: panelErrors,
    }
    return state
}

export function getParentForm(forms,form) {
    let formNames = {}
    Object.keys(forms).map(key => {
        const child = forms[key]
        formNames[child.name] = key
    })
    return forms[getParentFormName(formNames,form)]
}

export function getParentFormName(formNames,form) {
    return formNames[form.full_name.substring(0, form.full_name.indexOf('['))]
}

export function mergeParentForm(forms, name, form){
    forms[name] = {...form}
    return forms
}

export function replaceFormElement(form, element) {
    if (typeof form.children === 'object') {
        Object.keys(form.children).map(key => {
            let child = replaceFormElement(form.children[key],element)
            if (child.id === element.id)
                form.children[key] = element
        })
    } else if (typeof form.children === 'array') {
        form.children.map((child,key) => {
            child = replaceFormElement(child,element)
            if (child.id === element.id)
                form.children[key] = element
        })
    }
    if (form.id === element.id)
        form = element
    return form
}

export function replaceName(element, id) {
    element = {...element}
    if (typeof element.children === 'object') {
        element.children = {...element.children}
        Object.keys(element.children).map(childKey => {
            let child = replaceName(element.children[childKey], id)
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

export function deleteFormElement(form,element){
    if (typeof form.children === 'object') {
        Object.keys(form.children).map(key => {
            let child = deleteFormElement(form.children[key],element)
            if (child.id === element.id) {
                delete form.children[key]
            }
        })
    }
    if (typeof form.children === 'array') {
        form.children.map((child,key) => {
            child = deleteFormElement(child,element)
            if (child.id === element.id) {
                form.children.splice(key, 1)
            }
        })
    }
    return form
}

export function changeFormValue(form, find, value) {
    let newForm = {...form}
    if (typeof newForm.children === 'object' && Object.keys(newForm.children).length > 0) {
        let george = {...newForm.children}
        Object.keys(george).map(key => {
            let child = {...george[key]}
            if (child.id === find.id) {
                child.value = value
                Object.assign(george[key], {...child})
            } else {
                Object.assign(george[key], changeFormValue({...child}, find, value))
            }
        })
        Object.assign(newForm.children, {...george})
        return {...newForm}
    } else {
        return {...newForm}
    }
}


export function isSubmit(submit) {
    let result = false
    Object.keys(submit).map(key => {
        if (submit[key])
            result = true
    })
    return result
}

export function findElementById(form, id, element) {
    if (typeof element.id === 'string' && element.id === id)
        return element
    if (typeof form.children === 'object') {
        Object.keys(form.children).map(key => {
            let child = form.children[key]
            if (child.id === id)
                element = child
            element = findElementById(form.children[key],id,element)
        })
        return element
    }
    if (typeof form.children === 'array') {
        form.children.map((child, key) => {
            if (child.id === id)
                element = child
            element = findElementById(child,id,element)
        })
        return element
    }
    return element
}

export function buildFormData(data, form) {
    if (typeof form.children === 'object' && Object.keys(form.children).length > 0) {
        Object.keys(form.children).map(key => {
            let child = form.children[key]
            data[child.name] = buildFormData({}, child)
            //this.setMessageByElementErrors(child)
        })
        return data
    } else if (typeof form.children === 'array' && form.children.length > 0) {
            form.children.map(child => {
                data[child.name] = buildFormData({}, child)
                //this.setMessageByElementErrors(child)
            })
            return data
    } else {
        //this.setMessageByElementErrors(form)
        return form.value
    }
}
