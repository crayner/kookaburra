'use strict'

import React, { Component } from 'react'
import PropTypes from 'prop-types'
import CollectionRows from "./Template/Table/CollectionRows"
import {fetchJson} from "../component/fetchJson"

export default class CollectionApp extends Component {
    constructor (props) {
        super(props)
        this.form = props.form
        this.functions = props.functions

        this.functions.deleteElement = this.deleteElement.bind(this)
        this.state = {
            errors: [],
        }
    }

    deleteElement(form) {
        let route = this.form.element_delete_route
        if (typeof this.form.element_delete_options !== 'object') this.form.element_delete_options = {}
        Object.keys(this.form.element_delete_options).map(search => {
            let replace = this.form.element_delete_options[search]
            route = route.replace(search, form.children[replace].value)
        })
        fetchJson(route, [], false)
            .then((data) => {
                console.log(data)
                this.form = data.form === [] ? this.form : data.form
                this.setState({
                    errors: data.errors,
                })
            })
    }

    render() {
        return (<CollectionRows form={this.form} functions={this.functions} errors={this.state.errors} />)
    }
}

CollectionApp.propTypes = {
    form: PropTypes.object.isRequired,
    functions: PropTypes.object.isRequired,
}
