'use strict'

import React, { Component } from 'react'
import PropTypes from 'prop-types'

export default class FormToggle extends Component {
    constructor(props) {
        super(props)
        this.form = props.form
        this.errors = props.errors
        this.column = props.column

        let toggleState = this.form.value
        if (typeof toggleState === 'boolean')
            toggleState = toggleState === true ? '1' : '0'

        this.state = {
            toggleState: toggleState
        }

        this.toggleButton = this.toggleButton.bind(this)
    }

    toggleButton(e){
        this.setState({
            toggleState: this.state.toggleState === '1' ? '0' : '1'
        })
    }

    render() {
        return (<div className={this.column.wrapper.class + ' right'}>
            <label className={'switch'}>
                <input id={this.form.id} name={this.form.full_name} value={this.state.toggleState} onClick={(e) => this.toggleButton(e)} onChange={(e) => this.toggleButton(e)} />
                <span className={"slider round"} aria-describedby={this.form.id + '_help'}></span>
            </label>
        </div>)
    }
}

FormToggle.propTypes = {
    form: PropTypes.object.isRequired,
    column: PropTypes.object.isRequired,
    errors: PropTypes.array,
}
