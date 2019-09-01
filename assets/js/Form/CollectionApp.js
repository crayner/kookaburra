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
            count: 0,
            formCount: 0,
        }
    }

    componentDidMount() {
        Object.keys(this.form.prototype.children).map(key => {
            const child = this.form.prototype.children[key]
            if (child.type !== 'hidden') {
                this.functions.incColumnCount()
            }
        })
        this.functions.incColumnCount()

        this.setState({
            count: getChildCount(this.form),
            formCount: this.functions.calcFormCount(this.form, 0),
        })
    }

    incColumnCount(){
        this.columnCount++
    }

    getColumnCount(){
        return this.columnCount
    }

    render() {
        return (<CollectionRows form={this.form} functions={this.functions} errors={this.state.errors} />)
    }
}

CollectionApp.propTypes = {
    form: PropTypes.object.isRequired,
    functions: PropTypes.object.isRequired,
}

function getChildCount(form) {
    let count = 0
    Object.keys(form.children).map(key => {
        const child = form.children[key]
        if (typeof child === 'object')
            count++
    })
    return count
}

