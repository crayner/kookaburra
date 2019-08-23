'use strict'

import React, { Component } from 'react'
import PropTypes from 'prop-types'
import FormColumn from "./FormColumn"
import { generatePassword } from '../component/generatePassword'

export default class FormGeneratePassword extends Component {
    constructor(props) {
        super(props)
        this.form = props.form
        this.row = props.row

        this.state = {
            password: this.form.children.first.value
        }

        this.generateButton = this.generateButton.bind(this)
    }

    generateButton(){
        let row = this.form.children.first.row
        let password = generatePassword(row.passwordPolicy, )
        alert(row.translate['Copy this password if required'] + ': ' + password)
        this.setState({
            password: password
        })
    }

    render() {
        return Object.keys(this.form.children).map(childKey => {
            let child = this.form.children[childKey]
            child.type = 'password'
            child.value = this.state.password

            var row = child.row !== undefined ? child.row : {}
            row.style = row.style !== undefined && typeof(row.style) === 'object' ? row.style : {}

            const columns = row.columns.map((column,columnKey) => {
                column.style = column.style !== undefined && typeof(column.style) === 'object' ? column.style : {}

                if (child.name === 'first' && columnKey === 1) {
                    child.attr.class = child.attr.class !== undefined && typeof(child.attr.class) === 'string' ? child.attr.class : ''
                    child.attr.style = child.attr.style !== undefined && typeof(child.attr.style) === 'object' ? child.attr.style : {}
                    var attr = {...child.attr}
                    delete attr.style
                    delete attr.class
                    if (attr.inputmode !== undefined){
                        attr.inputMode = attr.inputmode
                        delete attr.inputmode
                    }
                    var errors = []
                    if (child.errors.length > 0) {
                        column.wrapper.class = column.wrapper.class + ' errors'
                        errors = child.errors.map((content,errorKey) => {
                            return (<li key={errorKey}>{content}</li>)
                        })
                    }

                    return (<td className={column.class} style={column.style} key={columnKey}>
                        <div className={column.wrapper.class}>
                            <input type={child.type} className={child.attr.class} style={child.attr.style} id={child.id}
                                   name={child.full_name} defaultValue={child.value} {...attr} aria-describedby={child.id + '_help'} />
                            <button type={'button'} className={row.button.class} onClick={this.generateButton} key={'generatePassword' + childKey}>{row.title}</button>
                            {child.errors.length > 0 ? <ul>{errors}</ul> : ''}
                        </div>
                    </td> )
                }

                return (<td className={column.class} style={column.style} key={columnKey}>
                    <FormColumn form={child} column={column} />
                </td> )
            })
            return (<tr className={row.class} style={row.style} key={childKey}>{columns}</tr>)
        })
    }
}

FormGeneratePassword.propTypes = {
    form: PropTypes.object.isRequired,
    row: PropTypes.object.isRequired,
}
