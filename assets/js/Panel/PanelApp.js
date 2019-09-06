'use strict'

import React from 'react'
import PropTypes from 'prop-types'
import Parser from "html-react-parser"
import Panels from "./Panels"
import FormApp from "../Form/FormApp"

export default function PanelApp(props) {
    const {
        panels,
        forms,
        selectedPanel,
        actionRoute,
        functions,
    } = props

    const tabIndex = panels[selectedPanel].index

    if (Object.keys(panels).length === 1) {
        const name = Object.keys(panels)[0]
        const panel = panels[name]
        if (panel.content !== null) {
            return (
                Parser(panel.content)
            )
        }
        return <FormApp {...props} form={forms[name]} functions={functions} />
    }
    return (
        <Panels {...props} panels={panels} selectedIndex={tabIndex} functions={functions} />
    )
}

PanelApp.propTypes = {
    panels: PropTypes.object.isRequired,
    forms: PropTypes.object.isRequired,
    selectedPanel: PropTypes.string,
    actionRoute: PropTypes.string,
    functions: PropTypes.object.isRequired,
}
