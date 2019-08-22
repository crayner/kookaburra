'use strict';

import React, { Component } from 'react'
import PropTypes from 'prop-types'
import FormInput from "./FormInput"

export default class FormWidget extends Component {
    constructor(props) {
        super(props)
        this.form = props.form
        console.log(this)
    }

    //  Work out which form element is required, build and return it.
    render() {
        var content = ''

        if (this.form.block_prefixes.includes('csrf_token')) {
            this.form.type = 'hidden'
            this.form.row = {}
            this.form.row.class = ''
            this.form.row.style = {}
            return (<FormInput form={this.form} />)
        }

        console.log(this.form)

        return (<div className={this.form.wrapper.class}><FormInput form={this.form} /></div>)
    }
}

FormWidget.propTypes = {
    form: PropTypes.object.isRequired,
}

