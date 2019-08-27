'use strict'

import React from "react"
import PropTypes from 'prop-types'
import {openPage} from "../component/openPage"
import {isEmpty} from '../component/isEmpty'

export default function FormInput(props) {
    const {
        form,
        functions,
    } = props

    let group = ''
    let button_attr = {}

    form.attr.class = form.attr.class !== undefined && typeof(form.attr.class) === 'string' ? form.attr.class : ''
    form.attr.style = form.attr.style !== undefined && typeof(form.attr.style) === 'object' ? form.attr.style : {}
    var widget_attr = {...form.attr}
    delete widget_attr.style
    delete widget_attr.class
    if (widget_attr.inputmode !== undefined){
        widget_attr.inputMode = widget_attr.inputmode
        delete widget_attr.inputmode
    }

    if (form.on_change !== null) {
        let onChange = this.functions[form.on_change]
        widget_attr.onChange = (e) => onChange(e)
    }

    if (form.block_prefixes.includes('csrf_token') || form.block_prefixes.includes('hidden') || typeof form.wrapper === undefined) {
        form.type = 'hidden'
        return (<input type={form.type} className={form.attr.class} style={form.attr.style} id={form.id}
                       name={form.full_name} defaultValue={form.value} {...widget_attr} aria-describedby={form.id + '_help'} />)
    }

    if (form.block_prefixes.includes('text')) {
        form.type = 'text'
    }

    if (form.block_prefixes.includes('file')) {
        form.type = 'file'
        if (isEmpty(form.value) ) {
            button_attr.disabled = true
        }
        group = (<button type={'button'} title={'File Download'} className={form.row.button.class} {...button_attr} onClick={() => downloadFile(form.value, form.row.security)}><span className={'fa-fw fas fa-file-download'}></span></button>)
    }

    if (form.block_prefixes.includes('url')) {
        form.type = 'url'
        if (isEmpty(form.value)) {
            button_attr.disabled = true
        }
        group = (<button type={'button'} title={'File Download'} className={form.row.button.class} {...button_attr} onClick={() => openUrl(form.value, form.row.security)}><span className={'fa-fw fas fa-external-link-alt'}></span></button>)
    }

    return (<div className={form.column.wrapper.class}><input type={form.type} className={form.attr.class} style={form.attr.style} id={form.id}
                                                       name={form.full_name} defaultValue={form.value} {...widget_attr} aria-describedby={form.id + '_help'} />{group}</div>)
}

FormInput.propTypes = {
    form: PropTypes.object.isRequired,
    functions: PropTypes.object.isRequired,
}

function downloadFile(file, security){

    const route = '/resource/' + btoa(file) + '/' + security + '/download/'

    openPage(route, {target: '_blank'}, false)
}

function openUrl(file) {
    window.open(file, '_blank')
}
