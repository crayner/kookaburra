'use strict'

import React from "react"
import PropTypes from 'prop-types'
import { Tab, Tabs, TabList, TabPanel } from 'react-tabs';
import '../../css/react-tabs.scss';
import Parser from "html-react-parser"
import FormApp from "../Form/FormApp"

export default function Panels(props) {
    const {
        panels,
        selectedIndex,
        functions,
    } = props

    const tabTags = Object.keys(panels).map(name => {
        const tab = panels[name]
        return (
            <Tab
                key={tab.name}
                disabled={tab.disabled}>
                {tab.label}
            </Tab>
        )
    })

    const content = Object.keys(panels).map(name => {
        const panelContent = renderPanelContent(panels[name], props)

        return (
            <TabPanel key={name}>
                {panelContent}
            </TabPanel>
        )
    })

    return (
        <Tabs selectedIndex={selectedIndex} onSelect={tabIndex => functions.onSelectTab(tabIndex)}>
            <TabList>
                {tabTags}
            </TabList>
            {content}
        </Tabs>
    )

}

Panels.propTypes = {
    panels: PropTypes.object.isRequired,
    forms: PropTypes.object.isRequired,
    functions: PropTypes.object.isRequired,
    selectedIndex: PropTypes.number.isRequired,
}

function renderPanelContent(panel, props){

    if (null !== panel.content){
        return Parser(panel.content)
    }

    let form = props.forms[panel.name]
    if (typeof form === 'undefined')
        form = props.forms['single']
    console.log(form)
    return <FormApp
        {...props}
        formName={panel.name}
        form={form} />
}

