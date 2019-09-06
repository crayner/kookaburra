'use strict'

import React, { Component } from 'react'
import PropTypes from 'prop-types'
import CollectionRows from "./Template/Table/CollectionRows"

export default class CollectionApp extends Component {
    constructor (props) {
        super(props)
        this.functions = props.functions
        this.form = props.form

        this.columnCount = 0

        this.functions.incColumnCount = this.incColumnCount.bind(this)
        this.functions.getColumnCount = this.getColumnCount.bind(this)

        this.state = {
            errors: [],
            count: getChildCount({...props.form}),
            formCount: this.functions.calcFormCount({...props.form}, 0),
            form: {...props.form},
        }
    }

    componentDidMount() {
        let form = {...this.state.form}
        if (typeof form.children === 'undefined')
            form.children = []
        Object.keys(form.prototype.children).map(key => {
            const child = form.prototype.children[key]
            if (child.type !== 'hidden') {
                this.functions.incColumnCount()
            }
        })
        this.functions.incColumnCount()

        this.setState({
            count: getChildCount(form),
            formCount: this.functions.calcFormCount(form, 0),
            form: form,
        })
    }

    incColumnCount(){
        this.columnCount++
    }

    getColumnCount(){
        return this.columnCount
    }

    render() {
        return (<CollectionRows form={this.state.form} functions={this.functions} errors={this.state.errors} columnCount={this.columnCount} key={this.form.collection_key} />)
    }
}

CollectionApp.propTypes = {
    form: PropTypes.object.isRequired,
    functions: PropTypes.object.isRequired,
}

function getChildCount(form) {
    let count = 0
    if (typeof form.children !== "undefined" && form.children.length > 0) {
        Object.keys(form.children).map(key => {
            const child = form.children[key]
            if (typeof child === 'object')
                count++
        })
    }
    return count
}

