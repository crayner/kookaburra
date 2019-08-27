'use strict';

import React, { Component } from 'react'
import PropTypes from 'prop-types'
import FormSelect from "./FormSelect"
import FormErrors from "./FormErrors"
import FormInput from "./FormInput"

export default class FormWidgetErrors extends Component {
    constructor(props) {
        super(props)
        this.form = props.form
        this.column = props.column
        this.functions = props.functions
    }

    //  Work out which form element is required, build and return it.
    render() {

        delete this.form.wrapper
        let wrapperClass = this.column.wrapper.class
        if (this.form.errors.length > 0)
            wrapperClass += ' errors'

        if (this.form.block_prefixes.includes('choice')) {
            return (<div className={wrapperClass}>
                <FormSelect form={this.form} functions={this.functions} />
                <FormErrors form={this.form} />
            </div>)
        }
console.log(this.form)
        return (<div className={wrapperClass}>
            <FormInput form={this.form} functions={this.functions} />
            <FormErrors form={this.form} />
        </div>)
    }
}

FormWidgetErrors.propTypes = {
    form: PropTypes.object.isRequired,
    column: PropTypes.object.isRequired,
    functions: PropTypes.object.isRequired,
}

