'use strict'

import React, { Component } from 'react'
import PropTypes from 'prop-types'
import {isEmpty} from "../component/isEmpty"

export default class FormInput extends Component {
    constructor(props) {
        super(props)
        this.form = props.form
    }

    render() {
        return (
            <input type={this.form.type} className={this.form.row.class} id={this.form.id} name={this.form.full_name}
                   defaultValue={this.form.value} style={this.form.row.style} />)
    }
}

FormInput.propTypes = {
    form: PropTypes.object.isRequired,
}

