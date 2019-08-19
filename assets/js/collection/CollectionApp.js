'use strict';

import React, { Component } from 'react'
import CollectionRender from "./CollectionRender"

export default class CollectionApp extends Component {
    constructor(props) {
        super(props)
        this.template = props.template
        this.form = props.form
        
        this.otherProps = {...props}
        this.onChange = this.onChange.bind(this)
        this.onClick = this.onClick.bind(this)
        this.state = {
            formData: this.buildFormData(this.form),
        }

        this.hideLinkFileElement = this.hideLinkFileElement.bind()
        this.buildFormData = this.buildFormData.bind(this)
    }

    buildFormData(form) {
        var formData = []
        form.value.map((values,elementKey) => {
            form.prototype.children.map((child, key) => {
                var result = {
                    row_class: typeof(child.row_class) === 'string' ? child.row_class.replace('__name__', elementKey) : null,
                    label: child.label !== false ? child.label.replace('__name__', elementKey) : null,
                    help: child.help !== '' ? child.help.replace('__name__', elementKey) : null,
                    help_attr: child.help_attr,
                    label_attr: child.label_attr,
                    attr: child.attr,
                    id: child.id.replace('__name__', elementKey),
                    full_name: child.full_name.replace('__name__', elementKey),
                    class: typeof(child.attr.class) === 'string' ? child.attr.class.replace('__name__', elementKey) : null,
                    name: child.name,
                    value: typeof(child.attr.dataValue) === 'string' ? values[child.attr.dataValue] : values[child.name],
                    block_prefixes: child.block_prefixes,
                    choices: child.choices !== undefined ? child.choices : null,
                    required: child.required,
                    onChange: child.attr.onChange !== undefined ? true : false,
                    onClick: child.attr.onClick !== undefined ? true : false,
                }
                if (formData[elementKey] === undefined){
                    formData[elementKey] = {}
                }
                formData[elementKey][result.name] = result
            })
        })

        const temp = formData
        temp.map((keys,elementKey) => {
            Object.keys(keys).map(key => {
                var child = keys[key]
                if (key === 'type') {
                    formData = this.hideLinkFileElement(elementKey, child.value, formData)
                }
            })
        })
        return formData
    }

    hideLinkFileElement(elementKey, value, formData) {

        formData[elementKey]['urlLink'].row_class = formData[elementKey]['urlLink'].row_class.replace(' hidden', '')
        formData[elementKey]['urlFile'].row_class = formData[elementKey]['urlFile'].row_class.replace(' hidden', '')

        if (value === 'Link') {
            formData[elementKey]['urlFile'].row_class = formData[elementKey]['urlFile'].row_class.replace('file'+elementKey, 'file'+elementKey+' hidden')
        } else {
            formData[elementKey]['urlLink'].row_class = formData[elementKey]['urlLink'].row_class.replace('link'+elementKey, 'link'+elementKey+' hidden')
        }

        formData[elementKey]['type'].value = value

        return formData
    }

    onChange(e) {

        var id = e.id.replace('_type', '')
        var key = id.replace('edit_resources_', '')
        var value = e.value

        var formData = this.state.formData
        formData[key]['type'].value = value

        this.setState({
            formData: this.hideLinkFileElement(key, value, formData)
        })
    }

    onClick(e) {
        console.log(e)
    }

    render () {
        return (<CollectionRender
                {...this.otherProps}
                formData={this.state.formData}
                form={this.form}
                template={{...this.template}}
                onChange={this.onChange}
                onClick={this.onClick}
            />
        )
    }

}

