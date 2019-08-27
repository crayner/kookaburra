'use strict';

import React, { Component } from 'react'
import PropTypes from 'prop-types'
import FormInput from "./FormInput"
import FormSelect from "./FormSelect"

export default class FormWidget extends Component {
    constructor(props) {
        super(props)
        this.form = {...props.form}
        this.functions = props.functions
        console.log(this)
    }

    //  Work out which form element is required, build and return it.
    render() {

        if (this.form.block_prefixes.includes('choice')) {
            return (<FormSelect form={this.form} functions={this.functions} />)
        }



        return (<FormInput form={this.form} functions={this.functions} />)
    }
}

FormWidget.propTypes = {
    form: PropTypes.object.isRequired,
    functions: PropTypes.object.isRequired,
}

