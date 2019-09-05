'use strict'

import React from "react"
import PropTypes from 'prop-types'
import {widgetAttr, wrapperAttr} from './buildAttr'
import CKEditor from '@ckeditor/ckeditor5-react';
import DocumentEditor from '@ckeditor/ckeditor5-build-classic';
import CollectionApp from "./CollectionApp"
import {isEmpty} from "../component/isEmpty"
import FormSelect from "./FormSelect"

export default function Widget(props) {
    const {
        form,
        functions,
    } = props

    let wrapper_attr = wrapperAttr(form, 'flex-1 relative')
    let element = 'form element ' + form.type
    let widget_attr = widgetAttr(form, 'w-full', functions)

    var errors = []
    if (form.errors.length > 0) {
        wrapper_attr.className += ' errors'
        errors = form.errors.map((content, errorKey) => {
            return (<li key={errorKey}>{content}</li>)
        })
    }

    if (form.type === 'ckeditor') {
        return (
            <div {...wrapper_attr}>
                <CKEditor editor={DocumentEditor} data={form.value} aria-describedby={form.id + '_help'} onChange={(event, editor) => functions.onCKEditorChange(event, editor, form)} />
                {form.errors.length > 0 ? <ul>{errors}</ul> : ''}
            </div>
        )
    }

    if (form.type === 'submit') {
        widget_attr.type = 'button'
        widget_attr.style = {float: 'right'}
        widget_attr.className = 'btn-gibbon'
        widget_attr.onClick = () => functions.submitForm()
        return (
            <div {...wrapper_attr}>
                <span className={'emphasis small'}>* {form.help}</span>
                <button {...widget_attr} >{form.label}</button>
            </div>
        )
    }

    if (form.type === 'hidden') {
        widget_attr.type = 'hidden'
        return (<input {...widget_attr} />)
    }

    if (form.type === 'email') {
        widget_attr.type = 'email'
        return (
            <div {...wrapper_attr}>
                <input {...widget_attr} defaultValue={form.value} />
                {form.errors.length > 0 ? <ul>{errors}</ul> : ''}
            </div>
        )
    }

    if (form.type === 'password_generator') {
        widget_attr.type = 'password'
        let button_attr = {}

        return (
            <div {...wrapper_attr}>
                <input {...widget_attr} defaultValue={form.value} />
                <button type={'button'} title={form.generateButton.title} className={form.generateButton.class} {...button_attr} onClick={() => functions[form.generateButton.onClick](form)}>{form.generateButton.title}</button>
                {form.errors.length > 0 ? <ul>{errors}</ul> : ''}
            </div>
        )
    }

    if (form.type === 'password') {
        widget_attr.type = 'password'
        return (
            <div {...wrapper_attr}>
                <input {...widget_attr} defaultValue={form.value} />
                {form.errors.length > 0 ? <ul>{errors}</ul> : ''}
            </div>
        )
    }

    if (form.type === 'url') {
        widget_attr.type = 'url'
        let button_attr = {}
        if (isEmpty(form.value)) {
            button_attr.disabled = true
        }

        return (
            <div {...wrapper_attr}>
                <input {...widget_attr} defaultValue={form.value} />
                <button type={'button'} title={functions.translate('Open Link')} className={'button button-right'} {...button_attr} onClick={() => functions.openUrl(form.value)}><span className={'fa-fw fas fa-external-link-alt'}></span></button>
                {form.errors.length > 0 ? <ul>{errors}</ul> : ''}
            </div>
        )
    }

    if (form.type === 'file') {
        widget_attr.type = 'file'
        let button_attr = {}
        if (isEmpty(form.value)) {
            button_attr.disabled = true
        }

        return (
            <div {...wrapper_attr}>
                <input {...widget_attr} />
                <div className={'button-right'}>
                <button type={'button'} title={functions.translate('File Download')} className={'button'} {...button_attr} onClick={() => functions.downloadFile(form.value)}><span className={'fa-fw fas fa-file-download'}></span></button>
                <button type={'button'} title={functions.translate('File Delete')} className={'button'} {...button_attr} onClick={() => functions.deleteFile(form)}><span className={'fa-fw fas fa-trash'}></span></button>
                </div>
                {form.errors.length > 0 ? <ul>{errors}</ul> : ''}
            </div>
        )
    }

    if (form.type === 'text') {
        widget_attr.type = 'text'
        return (
            <div {...wrapper_attr}>
                <input {...widget_attr} defaultValue={form.value} />
                {form.errors.length > 0 ? <ul>{errors}</ul> : ''}
            </div>
        )
    }


    if (form.type === 'collection') {
        return (<CollectionApp form={form} functions={functions} key={form.collection_key} />)
    }

    if (form.type === 'choice') {
        return (<FormSelect form={form} wrapper_attr={wrapper_attr} widget_attr={widget_attr}/>)
    }

    if (form.type === 'textarea') {
        return (
            <div {...wrapper_attr}>
                <textarea {...widget_attr} defaultValue={form.value} />
                {form.errors.length > 0 ? <ul>{errors}</ul> : ''}
            </div>
        )
    }

    if (form.type === 'toggle') {
        widget_attr.type = 'hidden'
        wrapper_attr.className += ' right'
        let button_attr = {}
        button_attr.onClick = (e) => functions.onElementChange(e, form)
        button_attr.className = 'button'
        delete widget_attr.onChange
        let span_attr = {}
        span_attr.className = 'fa-fw far fa-thumbs-down'
        if (form.value === 'Y' || form.value === '1') {
            span_attr.className = 'fa-fw far fa-thumbs-up'
            button_attr.className += ' success'
            form.value = 'Y'
        } else {
            form.value = 'N'
        }

        return (
            <div {...wrapper_attr}>
                <input {...widget_attr}  defaultValue={form.value} />
                <button type={'button'} title={functions.translate('Yes/No')} {...button_attr}><span {...span_attr}></span></button>
                {form.errors.length > 0 ? <ul>{errors}</ul> : ''}
            </div>
        )
    }

    if (form.type === 'integer') {
        widget_attr.type = 'number'
        return (
            <div {...wrapper_attr}>
                <input {...widget_attr} defaultValue={form.value} />
                {form.errors.length > 0 ? <ul>{errors}</ul> : ''}
            </div>
        )
    }

    console.log(form)
    return (<div {...wrapper_attr}>
        {element}
    </div>)
}

Widget.propTypes = {
    form: PropTypes.object.isRequired,
    functions: PropTypes.object.isRequired,
}