'use strict'

import React, { Component } from 'react'
import PropTypes from 'prop-types'
import PanelApp from "../Panel/PanelApp"
import {isEmpty} from '../component/isEmpty'
import {openPage} from "../component/openPage"

export default class ContainerApp extends Component {
    constructor (props) {
        super(props)
        this.panels = props.panels ? props.panels : {}
        this.content = props.content ? props.content : null
        this.selectedPanel = props.selectedPanel
        this.functions = props.functions
        this.translations = props.translations
        this.actionRoute = props.actionRoute
        this.forms = props.forms

        if (Object.keys(this.panels).length === 0 && this.content !== null) {
            this.panels['default'] = {}
            this.panels.default['name'] = 'default'
            this.panels.default['disabled'] = true
            this.panels.default['content'] = this.content
        }
        this.functions.translate = this.translate.bind(this)
        this.functions.openUrl = this.openUrl.bind(this)
        this.functions.downloadFile = this.downloadFile.bind(this)
    }

    translate(id){
        if (isEmpty(this.translations[id])) {
            console.error('Unable to translate: ' + id)
            return id
        }
        return this.translations[id]
    }

    downloadFile(file) {

        const route = '/resource/' + btoa(file) + '/' + this.actionRoute + '/download/'

        openPage(route, {target: '_blank'}, false)
    }

    openUrl(file) {
        window.open(file, '_blank')
    }

    render() {
        return (
            <PanelApp panels={this.panels} selectedPanel={this.selectedPanel} functions={this.functions} forms={this.forms} actionRoute={this.actionRoute} />
        )
    }
}

ContainerApp.propTypes = {
    panels: PropTypes.object,
    forms: PropTypes.object,
    functions: PropTypes.object,
    translations: PropTypes.object,
    content: PropTypes.string,
    actionRoutet: PropTypes.string,
    selectedPanel: PropTypes.string,
}

ContainerApp.defaultProps = {
    functions: {},
    translations: {},
    forms: {},
}
