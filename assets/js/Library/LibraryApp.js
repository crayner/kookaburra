'use strict'

import React, { Component } from 'react'
import ContainerApp from "../Container/ContainerApp"
import {fetchJson} from "../component/fetchJson"

export default class LibraryApp extends Component {
    constructor (props) {
        super(props)
        this.otherProps = {...props}

        this.state = {
            forms: props.forms,
            panelErrors: {},
            selectedPanel: props.selectedPanel
        }
        this.functions = {
            selectLibraryAndType: this.selectLibraryAndType.bind(this)
        }
    }

    componentDidMount() {

    }

    selectLibraryAndType(e){
        let form = this.state.forms['single']
        const id = e.target.id
        const value = e.target.value
        if (id === 'edit_library') {
            form.children.library.value = value
        }
        if (id === 'edit_libraryType') {
            form.children.libraryType.value = value
        }
        if (form.children.library.value > 0 && form.children.libraryType.value > 0) {
            let data = {
                library: form.children.library.value,
                libraryType: form.children.libraryType.value,
                _token: form.children._token.value,
            }
            fetchJson(
                form.action,
                {method: form.method, body: JSON.stringify(data)},
                false)
                .then(data => {
                    let errors = form.errors
                    errors = errors.concat(data.errors)
                    form = data.form
                    form.errors = errors
                    this.setState({
                        forms: {'single': {...form}},
                        selectedPanel: 'General',
                    })
                }).catch(error => {
                    form.errors.push({'class': 'error', 'message': error})
                    this.setState({
                        forms: {'single': {...form}}
                    })
                })
        }
    }

    render() {
        return (<ContainerApp {...this.otherProps} forms={this.state.forms} functions={this.functions} panelErrors={this.state.panelErrors} />)
    }
}
